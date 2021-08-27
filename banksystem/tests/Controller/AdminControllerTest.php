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
        $this->assertResponseRedirects();
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
        $crawler = $this->client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(200);
        $this->assertCount(1, $crawler->filter('h1'));
    }
}
