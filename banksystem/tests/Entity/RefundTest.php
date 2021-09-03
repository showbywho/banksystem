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
            ->setUserId(1)
            ->setUserName('test_user_name')
            ->setAmount(10)
            ->setCreateTime($this->testDate);

        $this->assertEquals(1, $this->refund->getId());
        $this->assertEquals(1, $this->refund->getUserId());
        $this->assertEquals('test_user_name', $this->refund->getUserName());
        $this->assertEquals(10, $this->refund->getAmount());
        $this->assertEquals($this->testDate, $this->refund->getCreateTime());
    }
}
