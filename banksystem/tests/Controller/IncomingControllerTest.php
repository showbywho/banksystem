<?php

namespace App\Tests\Controller;

use App\DataFixtures\AdminFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class IncomingControllerTest extends WebTestCase
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
        $this->client->request('GET', '/incoming');
        $this->assertResponseRedirects();
    }

    /**
     * 測試Index登入狀態
     */
    public function testIncomingIndex(): void
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/login',
            ['account' => 'admin', 'password' => 'test1234']
        );
        $crawler = $this->client->request('GET', '/incoming');
        $this->assertResponseStatusCodeSame(200);
        $this->assertCount(1, $crawler->filter('h1'));
    }

    /**
     * 測試redisIncoming未登入狀態
     */
    public function testRedisIncomingNotLogin(): void
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/redisIncoming',
            ['amount' => '1']
        );
        $actual = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('error', $actual['result']);
        $this->assertEquals('無權限執行此操作', $actual['msg']);
    }

    /**
     * 測試redisIncoming 帶入不合法金額
     */
    public function testRedisIncomingAmountCheck(): void
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/login',
            ['account' => 'admin', 'password' => 'test1234']
        );

        $this->client->xmlHttpRequest(
            'POST',
            '/redisIncoming',
            ['amount' => '0']
        );
        $actual = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('error', $actual['result']);
        $this->assertEquals('存款金額(0)不得為負數或0', $actual['msg']);
    }

    /**
     * 測試redisIncoming成功狀態
     */
    public function testRedisIncomingSuccess(): void
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/login',
            ['account' => 'admin', 'password' => 'test1234']
        );

        $this->client->xmlHttpRequest(
            'POST',
            '/redisIncoming',
            ['amount' => '1']
        );
        $actual = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('ok', $actual['result']);
        $this->assertEquals('存款成功', $actual['msg']);
        $this->assertEquals('11', $actual['ret']['balance']);
    }
}
