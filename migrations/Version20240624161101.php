<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240624161101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_event ADD course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE course_event ADD CONSTRAINT FK_D2050D4F591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('CREATE INDEX IDX_D2050D4F591CC992 ON course_event (course_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_event DROP FOREIGN KEY FK_D2050D4F591CC992');
        $this->addSql('DROP INDEX IDX_D2050D4F591CC992 ON course_event');
        $this->addSql('ALTER TABLE course_event DROP course_id');
    }
}
