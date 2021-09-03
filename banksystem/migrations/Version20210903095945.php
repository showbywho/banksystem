<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210903095945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin DROP roles,
            DROP session_id,
            DROP update_time,
            DROP status,
            CHANGE balance balance DOUBLE PRECISION NOT NULL,
            CHANGE total_refund total_refund DOUBLE PRECISION NOT NULL,
            CHANGE total_deposit total_deposit DOUBLE PRECISION NOT NULL,
            CHANGE version version INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE incoming DROP trade_no,
            DROP before_balance,
            DROP after_balance,
            DROP update_time,
            DROP status,
            DROP remark,
            CHANGE user_id user_Id INT NOT NULL COMMENT \'創建者ID\',
            CHANGE user_name user_name VARCHAR(255) NOT NULL COMMENT \'創建者暱稱\',
            CHANGE amount amount DOUBLE PRECISION NOT NULL COMMENT \'交易金額\',
            CHANGE create_time create_time DATETIME NOT NULL COMMENT \'創建時間\'');
        $this->addSql('ALTER TABLE refund DROP trade_no,
            DROP before_balance,
            DROP after_balance,
            DROP update_time,
            DROP status,
            DROP remark,
            CHANGE user_id user_id INT NOT NULL COMMENT \'創建者ID\',
            CHANGE user_name user_name VARCHAR(255) NOT NULL COMMENT \'創建者暱稱\',
            CHANGE amount amount DOUBLE PRECISION NOT NULL COMMENT \'交易金額\',
            CHANGE create_time create_time DATETIME NOT NULL COMMENT \'創建時間\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated,please modify it to your needs
        $this->addSql('ALTER TABLE admin ADD roles JSON NOT NULL,
            ADD session_id VARCHAR(255) CHARACTER
            SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
            ADD update_time DATETIME DEFAULT NULL,
            ADD status INT NOT NULL,
            CHANGE balance balance DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
            CHANGE total_refund total_refund DOUBLE PRECISION DEFAULT \'0\',
            CHANGE total_deposit total_deposit DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
            CHANGE version version INT NOT NULL');
        $this->addSql('ALTER TABLE incoming ADD trade_no VARCHAR(255) CHARACTER
        SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
            ADD before_balance DOUBLE PRECISION NOT NULL,
            ADD after_balance DOUBLE PRECISION NOT NULL,
            ADD update_time DATETIME DEFAULT NULL,
            ADD status INT NOT NULL,
            ADD remark VARCHAR(255) CHARACTER
            SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
            CHANGE user_Id user_id INT NOT NULL,
            CHANGE user_name user_name VARCHAR(255) CHARACTER
            SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
            CHANGE amount amount DOUBLE PRECISION NOT NULL,
            CHANGE create_time create_time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE refund ADD trade_no VARCHAR(255) CHARACTER
        SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
            ADD before_balance DOUBLE PRECISION NOT NULL,
            ADD after_balance DOUBLE PRECISION NOT NULL,
            ADD update_time DATETIME DEFAULT NULL,
            ADD status INT NOT NULL,
            ADD remark VARCHAR(255) CHARACTER
            SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
            CHANGE user_id user_id INT NOT NULL,
            CHANGE user_name user_name VARCHAR(255) CHARACTER
            SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
            CHANGE amount amount DOUBLE PRECISION NOT NULL,
            CHANGE create_time create_time DATETIME NOT NULL');
    }
}
