<?php

namespace App\Tests\Command;

use App\DataFixtures\AdminFixture;
use App\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class TimerCommandTest extends WebTestCase
{
    use FixturesTrait;
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->loadFixtures([
            AdminFixture::class,
        ]);

        $this->client->xmlHttpRequest(
            'POST',
            '/login',
            ['account' => 'admin', 'password' => 'test1234']
        );

        $redis = new \Predis\Client();
        $incomingArray = [
            'user_id' => '2',
            'amount' => '2',
            'order_id' => 'test001',
            'way' => 'plus',
        ];

        $serializedData = serialize($incomingArray);
        $redis->rpush('order_data', $serializedData);

        $refundArray = [
            'user_id' => '2',
            'amount' => '2',
            'order_id' => 'test001',
            'way' => 'minus',
        ];
        $serializedData = serialize($refundArray);
        $redis->rpush('order_data', $serializedData);

        $errorArray = [
            'user_id' => '2',
            'amount' => '2',
            'order_id' => 'test001',
            'way' => 'plus',
        ];

        $serializedData = serialize($errorArray);
        $serializedData = substr_replace($errorArray, 'makeErrorData', 0, 0);
        $redis->rpush('order_data', $serializedData);

        for ($i = 0; $i < 25; $i++) {
            $this->client->xmlHttpRequest(
                'POST',
                '/redisIncoming',
                ['amount' => '1']
            );

            $this->client->xmlHttpRequest(
                'POST',
                '/redisRefund',
                ['amount' => '1']
            );
        }

        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * 測試Command執行結果
     */
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->find('app:timer');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $admin = $this->entityManager
            ->getRepository(Admin::class)
            ->findOneBy(['id' => '1']);
        $this->assertGreaterThan('1', $admin->getVersion());
    }
}
