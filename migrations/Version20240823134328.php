<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240823134328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_entry DROP FOREIGN KEY FK_C28A9A984668FEC0');
        $this->addSql('ALTER TABLE course_entry DROP FOREIGN KEY FK_C28A9A98BA8F9677');
        $this->addSql('ALTER TABLE course_entry DROP FOREIGN KEY FK_C28A9A98591CC992');
        $this->addSql('DROP INDEX IDX_C28A9A98BA8F9677 ON course_entry');
        $this->addSql('DROP INDEX IDX_C28A9A98591CC992 ON course_entry');
        $this->addSql('DROP INDEX IDX_C28A9A984668FEC0 ON course_entry');
        $this->addSql('ALTER TABLE course_entry ADD course VARCHAR(255) NOT NULL, ADD planned_teacher VARCHAR(255) DEFAULT NULL, ADD updated_teacher VARCHAR(255) DEFAULT NULL, DROP course_id, DROP planned_teacher_id, DROP updated_teacher_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_entry ADD course_id INT DEFAULT NULL, ADD planned_teacher_id INT DEFAULT NULL, ADD updated_teacher_id INT DEFAULT NULL, DROP course, DROP planned_teacher, DROP updated_teacher');
        $this->addSql('ALTER TABLE course_entry ADD CONSTRAINT FK_C28A9A984668FEC0 FOREIGN KEY (planned_teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE course_entry ADD CONSTRAINT FK_C28A9A98BA8F9677 FOREIGN KEY (updated_teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE course_entry ADD CONSTRAINT FK_C28A9A98591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('CREATE INDEX IDX_C28A9A98BA8F9677 ON course_entry (updated_teacher_id)');
        $this->addSql('CREATE INDEX IDX_C28A9A98591CC992 ON course_entry (course_id)');
        $this->addSql('CREATE INDEX IDX_C28A9A984668FEC0 ON course_entry (planned_teacher_id)');
    }
}
