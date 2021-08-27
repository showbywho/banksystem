<?php

namespace App\Tests\Controller;

use App\DataFixtures\AdminFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class LoginControllerTest extends WebTestCase
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
     * 測試Index未登入頁面
     */
    public function testIndexNotLogin(): void
    {
        $crawler = $this->client->xmlHttpRequest(
            'GET',
            '/login'
        );
        $this->assertResponseStatusCodeSame(200);
        $this->assertCount(1, $crawler->filter('h3'));
    }

    /**
     * 測試Index登入成功
     */
    public function testLogin(): void
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/login',
            ['account' => 'admin', 'password' => 'test1234']
        );
        $actual = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals('ok', $actual['result']);
        $this->assertEquals('登入成功', $actual['msg']);
        $this->assertEquals('1', $actual['ret']['id']);
        $this->assertEquals('calvin', $actual['ret']['nick_name']);
    }

    /**
     * 測試Index登出成功
     */
    public function testLogOut(): void
    {
        $this->client->request('GET', '/logout');
        $actual = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals('ok', $actual['result']);
        $this->assertEquals('登出成功', $actual['msg']);
    }
}
