<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210804014331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE incoming (
            id INT AUTO_INCREMENT NOT NULL
            , trade_no VARCHAR(255) NOT NULL COMMENT "交易訂單號"
            , user_id INT NOT NULL COMMENT "創建者ID"
            , user_name VARCHAR(255) NOT NULL COMMENT "創建者暱稱"
            , amount DOUBLE PRECISION NOT NULL COMMENT "交易金額"
            , before_balance DOUBLE PRECISION NOT NULL COMMENT "交易前餘額"
            , after_balance DOUBLE PRECISION NOT NULL COMMENT "交易後餘額"
            , create_time DATETIME NOT NULL COMMENT "創建時間"
            , update_time DATETIME DEFAULT NULL COMMENT "訂單更新時間"
            , status INT NOT NULL COMMENT "訂單狀態"
            , remark VARCHAR(255) DEFAULT NULL COMMENT "備註"
            , PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE refund (
            id INT AUTO_INCREMENT NOT NULL
            , trade_no VARCHAR(255) NOT NULL COMMENT "交易訂單號"
            , user_id INT NOT NULL COMMENT "創建者ID"
            , user_name VARCHAR(255) NOT NULL COMMENT "創建者暱稱"
            , amount DOUBLE PRECISION NOT NULL COMMENT "交易金額"
            , before_balance DOUBLE PRECISION NOT NULL COMMENT "交易前餘額"
            , after_balance DOUBLE PRECISION NOT NULL COMMENT "交易後餘額"
            , create_time DATETIME NOT NULL COMMENT "創建時間"
            , update_time DATETIME DEFAULT NULL COMMENT "訂單更新時間"
            , status INT NOT NULL COMMENT "訂單狀態"
            , remark VARCHAR(255) DEFAULT NULL COMMENT "備註"
            , PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE incoming');
        $this->addSql('DROP TABLE refund');
    }
}
