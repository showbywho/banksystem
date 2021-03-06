<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210804022406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE admin (
                id INT AUTO_INCREMENT NOT NULL
                , account VARCHAR(180) NOT NULL
                , roles JSON NOT NULL, password VARCHAR(255) NOT NULL
                , nick_name VARCHAR(255) NOT NULL
                , balance DOUBLE PRECISION NOT NULL
                , total_refund DOUBLE PRECISION NOT NULL
                , session_id VARCHAR(255) DEFAULT NULL
                , total_deposit DOUBLE PRECISION NOT NULL
                , create_time DATETIME NOT NULL
                , update_time DATETIME DEFAULT NULL, status INT NOT NULL
                , UNIQUE INDEX UNIQ_880E0D767D3656A4 (account)
                , PRIMARY KEY(id))'
            );
        $this->addSql('ALTER TABLE incoming CHANGE
            trade_no trade_no VARCHAR(255) NOT NULL
            , CHANGE user_id user_id INT NOT NULL
            , CHANGE user_name user_name VARCHAR(255) NOT NULL
            , CHANGE amount amount DOUBLE PRECISION NOT NULL
            , CHANGE before_balance before_balance DOUBLE PRECISION NOT NULL
            , CHANGE after_balance after_balance DOUBLE PRECISION NOT NULL
            , CHANGE create_time create_time DATETIME NOT NULL
            , CHANGE update_time update_time DATETIME DEFAULT NULL
            , CHANGE status status INT NOT NULL
            , CHANGE remark remark VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE refund CHANGE
            trade_no trade_no VARCHAR(255) NOT NULL
            , CHANGE user_id user_id INT NOT NULL
            , CHANGE user_name user_name VARCHAR(255) NOT NULL
            , CHANGE amount amount DOUBLE PRECISION NOT NULL
            , CHANGE before_balance before_balance DOUBLE PRECISION NOT NULL
            , CHANGE after_balance after_balance DOUBLE PRECISION NOT NULL
            , CHANGE create_time create_time DATETIME NOT NULL
            , CHANGE update_time update_time DATETIME DEFAULT NULL
            , CHANGE status status INT NOT NULL
            , CHANGE remark remark VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE admin');
        $this->addSql('ALTER TABLE incoming CHANGE
            trade_no trade_no VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'???????????????\'
            , CHANGE user_id user_id INT NOT NULL COMMENT \'?????????ID\'
            , CHANGE user_name user_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'???????????????\'
            , CHANGE amount amount DOUBLE PRECISION NOT NULL COMMENT \'????????????\'
            , CHANGE before_balance before_balance DOUBLE PRECISION NOT NULL COMMENT \'???????????????\'
            , CHANGE after_balance after_balance DOUBLE PRECISION NOT NULL COMMENT \'???????????????\'
            , CHANGE create_time create_time DATETIME NOT NULL COMMENT \'????????????\'
            , CHANGE update_time update_time DATETIME DEFAULT NULL COMMENT \'??????????????????\'
            , CHANGE status status INT NOT NULL COMMENT \'????????????\'
            , CHANGE remark remark VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'??????\'');
        $this->addSql('ALTER TABLE refund CHANGE
            trade_no trade_no VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'???????????????\'
            , CHANGE user_id user_id INT NOT NULL COMMENT \'?????????ID\'
            , CHANGE user_name user_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'???????????????\'
            , CHANGE amount amount DOUBLE PRECISION NOT NULL COMMENT \'????????????\'
            , CHANGE before_balance before_balance DOUBLE PRECISION NOT NULL COMMENT \'???????????????\'
            , CHANGE after_balance after_balance DOUBLE PRECISION NOT NULL COMMENT \'???????????????\'
            , CHANGE create_time create_time DATETIME NOT NULL COMMENT \'????????????\'
            , CHANGE update_time update_time DATETIME DEFAULT NULL COMMENT \'??????????????????\'
            , CHANGE status status INT NOT NULL COMMENT \'????????????\'
            , CHANGE remark remark VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'??????\'');
    }
}
