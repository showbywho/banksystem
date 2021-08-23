<?php

namespace App\Command;

use App\Entity\Admin;
use App\Entity\Incoming;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\OptimisticLockException;

class TimerCommand extends Command
{
    protected static $defaultName = 'app:timer';

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('更新資料庫訂單及餘額')
            ->setHelp('這個命令可以使redis的資料更新至database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $batchSize = 20;
        $redis = new \Predis\Client();
        $redisOrderDataLen = $redis->llen('order_data');
        $totalExec = ceil($redisOrderDataLen / $batchSize);

        for ($i = 1; $i <= $totalExec; $i++) {
            $batchSizeQueue = $redis->lrange('order_data', 0, $batchSize - 1);
            $errorKey = '';
            $this->em->getConnection()->beginTransaction();
            try {
                foreach ($batchSizeQueue as $key => $value) {
                    $errorKey = $key;
                    $redisOrderData = unserialize($value);
                    $userId = $redisOrderData['user_id'];
                    $amount = $redisOrderData['amount'];
                    $way = $redisOrderData['way'];
                    $orderId = $redisOrderData['order_id'];

                    if ($way == 'plus') {
                        $incoming = $this->em
                            ->getRepository(Incoming::class)
                            ->findOneBy([
                                'id' => $orderId,
                                'amount' => $amount,
                            ]);

                        if ($incoming) {
                            $incoming->setStatus(Admin::SUCCESS);
                            $admin = $this->em->find('App\Entity\Admin', $userId);

                            if ($admin) {
                                $admin->plusBalance($amount);
                                $admin->plusTotalDeposit($amount);
                                $redis->decrby("user:$userId", $amount);
                            }
                        }
                    }

                    if ($way == 'minus') {
                        $refund = $this->em
                            ->getRepository('App\Entity\Refund')
                            ->findOneBy([
                                'id' => $orderId,
                                'amount' => $amount,
                            ]);

                        if ($refund) {
                            $refund->setStatus(Admin::SUCCESS);
                            $admin = $this->em->find('App\Entity\Admin', $userId);

                            if ($admin) {
                                $admin->minusBalance($amount);
                                $admin->minusTotalRefund($amount);
                                $redis->incrby("user:$userId", $amount);
                            }
                        }
                    }
                }

                $this->em->flush();
                $this->em->clear();
                $this->em->getConnection()->commit();
                $redis->ltrim('order_data', $batchSize, $redisOrderDataLen);
            } catch (\Exception $e) {
                $errorData = $redis->lindex('order_data', $errorKey);
                $redis->rpush('error_order_data', $errorData);
                $redis->lrem('order_data', $errorKey, $errorData);
                $this->em->getConnection()->rollBack();
                $totalExec = ceil(($redisOrderDataLen - 1) / $batchSize);
                $i--;
            }
        }

        return Command::SUCCESS;
    }
}
