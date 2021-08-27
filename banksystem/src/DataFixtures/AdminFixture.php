<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdminFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new Admin();
        $admin->setId(1)
            ->setAccount('admin')
            ->setRoles(['ROLE_USER'])
            ->setPassword(MD5('test1234'))
            ->setBalance(10)
            ->setTotalRefund(10)
            ->setSessionId('test_session_id')
            ->setTotalDeposit(10)
            ->setCreateTime(new \DateTime())
            ->setUpdateTime(new \DateTime())
            ->setStatus(1)
            ->setNickName('calvin')
            ->plusBalance(10)
            ->plusTotalDeposit(10)
            ->minusBalance(10)
            ->minusTotalRefund(10);
        $manager->persist($admin);
        $manager->flush();
    }
}
