<?php

namespace App\Tests\Entity;

use App\Entity\Refund;
use PHPUnit\Framework\TestCase;

class RefundTest extends TestCase
{
    private $refund;
    private $testDate;

    protected function setUp(): void
    {
        $this->refund = new Refund();
        $this->testDate = new \DateTime();
    }

    /**
     * 測試RefundEntity
     */
    public function testRefundEntity(): void
    {
        $this->refund
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

        $this->assertEquals(1, $this->refund->getId());
        $this->assertEquals('test_trade_no', $this->refund->getTradeNo());
        $this->assertEquals(1, $this->refund->getUserId());
        $this->assertEquals('test_user_name', $this->refund->getUserName());
        $this->assertEquals(10, $this->refund->getAmount());
        $this->assertEquals(10, $this->refund->getBeforeBalance());
        $this->assertEquals(20, $this->refund->getAfterBalance());
        $this->assertEquals($this->testDate, $this->refund->getCreateTime());
        $this->assertEquals($this->testDate, $this->refund->getUpdateTime());
        $this->assertEquals(3, $this->refund->getStatus());
        $this->assertEquals('test_remark', $this->refund->getRemark());
    }
}
