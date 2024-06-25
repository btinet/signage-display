<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625104146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_entry (id INT AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, schedule_type_id INT DEFAULT NULL, planned_teacher_id INT DEFAULT NULL, updated_teacher_id INT DEFAULT NULL, entry_date DATE NOT NULL, entry_time VARCHAR(255) NOT NULL, planned_room VARCHAR(255) NOT NULL, updated_room VARCHAR(255) DEFAULT NULL, message VARCHAR(255) DEFAULT NULL, INDEX IDX_C28A9A98591CC992 (course_id), INDEX IDX_C28A9A984826A022 (schedule_type_id), INDEX IDX_C28A9A984668FEC0 (planned_teacher_id), INDEX IDX_C28A9A98BA8F9677 (updated_teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_entry ADD CONSTRAINT FK_C28A9A98591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE course_entry ADD CONSTRAINT FK_C28A9A984826A022 FOREIGN KEY (schedule_type_id) REFERENCES schedule_type (id)');
        $this->addSql('ALTER TABLE course_entry ADD CONSTRAINT FK_C28A9A984668FEC0 FOREIGN KEY (planned_teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE course_entry ADD CONSTRAINT FK_C28A9A98BA8F9677 FOREIGN KEY (updated_teacher_id) REFERENCES teacher (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_entry DROP FOREIGN KEY FK_C28A9A98591CC992');
        $this->addSql('ALTER TABLE course_entry DROP FOREIGN KEY FK_C28A9A984826A022');
        $this->addSql('ALTER TABLE course_entry DROP FOREIGN KEY FK_C28A9A984668FEC0');
        $this->addSql('ALTER TABLE course_entry DROP FOREIGN KEY FK_C28A9A98BA8F9677');
        $this->addSql('DROP TABLE course_entry');
    }
}
