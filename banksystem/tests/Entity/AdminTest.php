<?php

namespace App\Tests\Entity;

use App\Entity\Admin;
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase
{
    private $admin;
    private $testDate;

    protected function setUp(): void
    {
        $this->admin = new Admin();
        $this->testDate = new \DateTime();
    }

    /**
     * 測試AdminEntity
     */
    public function testAdminEntity(): void
    {
        $this->admin
            ->setId(2)
            ->setVersion(1)
            ->setAccount('admin')
            ->setPassword(MD5('test_password'))
            ->setBalance(0)
            ->setTotalRefund(0)
            ->setTotalDeposit(0)
            ->setCreateTime($this->testDate)
            ->setNickName('calvin')
            ->plusBalance(10)
            ->plusTotalDeposit(10)
            ->minusBalance(10)
            ->minusTotalRefund(10);

        $this->assertEquals(2, $this->admin->getId());
        $this->assertEquals(1, $this->admin->getVersion());
        $this->assertEquals('admin', $this->admin->getAccount());
        $this->assertEquals(MD5('test_password'), $this->admin->getPassword());
        $this->assertEquals(0, $this->admin->getBalance());
        $this->assertEquals(10, $this->admin->getTotalRefund());
        $this->assertEquals(10, $this->admin->getTotalDeposit());
        $this->assertEquals($this->testDate, $this->admin->getCreateTime());
        $this->assertEquals('calvin', $this->admin->getNickName());
    }
}
