<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\LockMode;

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
        $batchSize = 50;
        $loopRange = 1000;
        $redis = new \Predis\Client();
        $rangeOrderData = [];

        for ($i = 0; $i < $loopRange; $i++) {
            $redisPop = $redis->lpop('order_data');

            if (empty($redisPop)) {
                break;
            }

            $rangeOrderData[] = $redisPop;
        }

        $countArrayLen = count($rangeOrderData);
        $totalExec = ceil($countArrayLen / $batchSize);

        for ($i = 0; $i < $totalExec; $i++) {
            $range = $i * $batchSize;

            $this->em->beginTransaction();
            try {
                for ($b = 0; $b < $batchSize; $b++) {
                    $key = $b + $range;

                    if (!isset($rangeOrderData[$key])) {
                        break;
                    }

                    $originalData = $rangeOrderData[$key];
                    $redisOrderData = unserialize($originalData);
                    $userId = $redisOrderData['user_id'];
                    $amount = $redisOrderData['amount'];
                    $way = $redisOrderData['way'];
                    $admin = $this->em->find('App\Entity\Admin', $userId);

                    if ($way === 'plus' && $admin) {
                        $version = $admin->getVersion();
                        $this->em->lock(
                            $admin,
                            LockMode::OPTIMISTIC,
                            $version,
                        );

                        $admin->plusBalance($amount);
                        $admin->plusTotalDeposit($amount);
                    }

                    if ($way === 'minus' && $admin) {
                        $version = $admin->getVersion();
                        $this->em->lock(
                            $admin,
                            LockMode::OPTIMISTIC,
                            $version,
                        );

                        $admin->minusBalance($amount);
                        $admin->minusTotalRefund($amount);
                    }
                }

                $this->em->flush();
                $this->em->commit();
            } catch (\Exception $e) {
                $this->em->rollBack();
                $this->em->clear();
                $errorDataArray = array_slice($rangeOrderData, $range, $batchSize);
                $redis->rpush('error_order_data', $errorDataArray);
            }
        }

        return Command::SUCCESS;
    }
}
