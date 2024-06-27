<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627053655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message_type (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, css_class VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shout_out (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, disabled TINYINT(1) NOT NULL, INDEX IDX_D3AF1DBEC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shout_out ADD CONSTRAINT FK_D3AF1DBEC54C8C93 FOREIGN KEY (type_id) REFERENCES message_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shout_out DROP FOREIGN KEY FK_D3AF1DBEC54C8C93');
        $this->addSql('DROP TABLE message_type');
        $this->addSql('DROP TABLE shout_out');
    }
}
