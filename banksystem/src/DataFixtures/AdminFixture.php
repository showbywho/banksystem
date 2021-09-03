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
            ->setPassword(MD5('test1234'))
            ->setBalance(10)
            ->setTotalRefund(10)
            ->setTotalDeposit(10)
            ->setCreateTime(new \DateTime())
            ->setNickName('calvin')
            ->plusBalance(10)
            ->plusTotalDeposit(10)
            ->minusBalance(10)
            ->minusTotalRefund(10);
        $manager->persist($admin);
        $manager->flush();
    }
}
