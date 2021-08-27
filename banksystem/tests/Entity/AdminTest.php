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
            ->setRoles(['ROLE_USER'])
            ->setPassword(MD5('test_password'))
            ->setBalance(0)
            ->setTotalRefund(0)
            ->setSessionId('test_session_id')
            ->setTotalDeposit(0)
            ->setCreateTime($this->testDate)
            ->setUpdateTime($this->testDate)
            ->setStatus(1)
            ->setNickName('calvin')
            ->plusBalance(10)
            ->plusTotalDeposit(10)
            ->minusBalance(10)
            ->minusTotalRefund(10);

        $this->assertEquals(2, $this->admin->getId());
        $this->assertEquals(1, $this->admin->getVersion());
        $this->assertEquals('admin', $this->admin->getAccount());
        $this->assertEquals(['ROLE_USER'], $this->admin->getRoles());
        $this->assertEquals(MD5('test_password'), $this->admin->getPassword());
        $this->assertEquals(0, $this->admin->getBalance());
        $this->assertEquals(10, $this->admin->getTotalRefund());
        $this->assertEquals('test_session_id', $this->admin->getSessionId());
        $this->assertEquals(10, $this->admin->getTotalDeposit());
        $this->assertEquals($this->testDate, $this->admin->getCreateTime());
        $this->assertEquals($this->testDate, $this->admin->getUpdateTime());
        $this->assertEquals(1, $this->admin->getStatus());
        $this->assertEquals('calvin', $this->admin->getNickName());
        $this->assertEquals(0, $this->admin->getBalance());
        $this->assertEquals(10, $this->admin->getTotalDeposit());
        $this->assertEquals(10, $this->admin->getTotalRefund());
    }
}
