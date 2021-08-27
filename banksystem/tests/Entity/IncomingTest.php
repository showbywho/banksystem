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
            ->setTradeNo('test_trade_no')
            ->setUserId(1)
            ->setUserName('test_user_name')
            ->setAmount(10)
            ->setBeforeBalance(10)
            ->setAfterBalance(20)
            ->setCreateTime($this->testDate)
            ->setUpdateTime($this->testDate)
            ->setStatus(3)
            ->setRemark('test_remark');

        $this->assertEquals(1, $this->incoming->getId());
        $this->assertEquals('test_trade_no', $this->incoming->getTradeNo());
        $this->assertEquals(1, $this->incoming->getUserId());
        $this->assertEquals('test_user_name', $this->incoming->getUserName());
        $this->assertEquals(10, $this->incoming->getAmount());
        $this->assertEquals(10, $this->incoming->getBeforeBalance());
        $this->assertEquals(20, $this->incoming->getAfterBalance());
        $this->assertEquals($this->testDate, $this->incoming->getCreateTime());
        $this->assertEquals($this->testDate, $this->incoming->getUpdateTime());
        $this->assertEquals(3, $this->incoming->getStatus());
        $this->assertEquals('test_remark', $this->incoming->getRemark());
    }
}
