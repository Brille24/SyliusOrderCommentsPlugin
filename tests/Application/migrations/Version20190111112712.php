<?php declare(strict_types=1);

namespace Sylius\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190111112712 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sylius_order_comment (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', order_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, authorEmail_email VARCHAR(255) NOT NULL, attachedFile_path VARCHAR(255) DEFAULT NULL, INDEX IDX_8EA9CF098D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_order_comment ADD CONSTRAINT FK_8EA9CF098D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sylius_order_comment');
    }
}
