<?php

namespace App\Tests\Controller;

use App\DataFixtures\AdminFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class AdminControllerTest extends WebTestCase
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
        $this->client->request('GET', '/admin');
        $actual = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('error', $actual['result']);
        $this->assertEquals('沒有存取權限,請先登入!', $actual['msg']);
    }

    /**
     * 測試Index登入成功
     */
    public function testAdminIndex(): void
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/login',
            ['account' => 'admin', 'password' => 'test1234']
        );
        $this->client->request('GET', '/admin');
        $actual = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('ok', $actual['result']);
        $this->assertEquals('成功存取', $actual['msg']);
        $this->assertEquals('1', $actual['ret']['user_data']['id']);
        $this->assertEquals('admin', $actual['ret']['user_data']['account']);
        $this->assertEquals('10', $actual['ret']['user_data']['balance']);
        $this->assertEquals('20', $actual['ret']['user_data']['totalRefund']);
        $this->assertEquals('20', $actual['ret']['user_data']['totalDeposit']);
        $this->assertEquals('1', $actual['ret']['user_data']['version']);
        $this->assertEquals('calvin', $actual['ret']['user_data']['nickName']);
    }
}
