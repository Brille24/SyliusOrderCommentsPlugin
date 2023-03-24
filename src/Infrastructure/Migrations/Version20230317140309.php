<?php

declare(strict_types=1);

namespace Brille24\SyliusOrderCommentsPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230317140309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sylius_order_comment (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', order_id INT DEFAULT NULL, message TEXT NOT NULL, createdAt DATETIME NOT NULL, notifyCustomer TINYINT(1) NOT NULL, authorEmail_email VARCHAR(255) NOT NULL, attachedFile_path VARCHAR(255) DEFAULT NULL, INDEX IDX_8EA9CF098D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_order_comment ADD CONSTRAINT FK_8EA9CF098D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_order_comment DROP FOREIGN KEY FK_8EA9CF098D9F6D38');
        $this->addSql('DROP TABLE sylius_order_comment');
    }
}
