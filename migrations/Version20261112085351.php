<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261112085351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE running_display_macro (id INT AUTO_INCREMENT NOT NULL, display_macro_id INT DEFAULT NULL, INDEX IDX_6BC9652093D0BD50 (display_macro_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE running_display_macro ADD CONSTRAINT FK_6BC9652093D0BD50 FOREIGN KEY (display_macro_id) REFERENCES display_macro (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE running_display_macro DROP FOREIGN KEY FK_6BC9652093D0BD50');
        $this->addSql('DROP TABLE running_display_macro');
    }
}
