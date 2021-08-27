<?php

namespace App\Tests\Controller;

use App\DataFixtures\AdminFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class RefundControllerTest extends WebTestCase
{
    use FixturesTrait;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->loadFixtures([
            AdminFixture::class,
        ]);
    }

    /**
     * 測試Index未登入狀態
     */
    public function testIndexNotLogin(): void
    {
        $this->client->request('GET', '/refund');
        $this->assertResponseRedirects();
    }

    /**
     * 測試Index登入成功畫面
     */
    public function testRefundIndex(): void
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/login',
            ['account' => 'admin', 'password' => 'test1234']
        );
        $crawler = $this->client->request('GET', '/refund');
        $this->assertResponseStatusCodeSame(200);
        $this->assertCount(1, $crawler->filter('h1'));
    }

    /**
     * 測試redisRefund未登入狀態
     */
    public function testRedisRefundNotLogin(): void
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/redisRefund',
            ['amount' => '1']
        );
        $actual = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('error', $actual['result']);
        $this->assertEquals('無權限執行此操作', $actual['msg']);
    }

    /**
     * 測試redisRefund 帶入不合法金額
     */
    public function testRedisRefundAmountCheck(): void
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/login',
            ['account' => 'admin', 'password' => 'test1234']
        );

        $this->client->xmlHttpRequest(
            'POST',
            '/redisRefund',
            ['amount' => '0']
        );
        $actual = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('error', $actual['result']);
        $this->assertEquals('提現金額(0)不得為負數或0', $actual['msg']);
    }

    /**
     * 測試redisRefund 帶入超出餘額額度金額
     */
    public function testRedisRefundBalanceCheck(): void
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/login',
            ['account' => 'admin', 'password' => 'test1234']
        );

        $this->client->xmlHttpRequest(
            'POST',
            '/redisRefund',
            ['amount' => '100']
        );
        $actual = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('error', $actual['result']);
        $this->assertEquals('餘額(10)不足無法提現', $actual['msg']);
    }

    /**
     * 測試redisRefund成功狀態
     */
    public function testRedisRefundSuccess(): void
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/login',
            ['account' => 'admin', 'password' => 'test1234']
        );

        $this->client->xmlHttpRequest(
            'POST',
            '/redisRefund',
            ['amount' => '1']
        );
        $actual = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('ok', $actual['result']);
        $this->assertEquals('提現成功', $actual['msg']);
        $this->assertEquals('9', $actual['ret']['balance']);
    }
}
