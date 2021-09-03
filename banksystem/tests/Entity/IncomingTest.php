<?php

namespace App\Tests\Entity;

use App\Entity\Incoming;
use PHPUnit\Framework\TestCase;

class IncomingTest extends TestCase
{
    private $incoming;
    private $testDate;

    protected function setUp(): void
    {
        $this->incoming = new Incoming();
        $this->testDate = new \DateTime();
    }

    /**
     * 測試IncomingEntity
     */
    public function testIncomingEntity(): void
    {
        $this->incoming
            ->setId(1)
            ->setUserId(1)
            ->setUserName('test_user_name')
            ->setAmount(10)
            ->setCreateTime($this->testDate);

        $this->assertEquals(1, $this->incoming->getId());
        $this->assertEquals(1, $this->incoming->getUserId());
        $this->assertEquals('test_user_name', $this->incoming->getUserName());
        $this->assertEquals(10, $this->incoming->getAmount());
        $this->assertEquals($this->testDate, $this->incoming->getCreateTime());
    }
}
