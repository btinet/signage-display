<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627170852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_post_list_entry (blog_post_id INT NOT NULL, list_entry_id INT NOT NULL, INDEX IDX_63337C31A77FBEAF (blog_post_id), INDEX IDX_63337C31C45B7A22 (list_entry_id), PRIMARY KEY(blog_post_id, list_entry_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_post_list_entry ADD CONSTRAINT FK_63337C31A77FBEAF FOREIGN KEY (blog_post_id) REFERENCES blog_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_post_list_entry ADD CONSTRAINT FK_63337C31C45B7A22 FOREIGN KEY (list_entry_id) REFERENCES list_entry (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post_list_entry DROP FOREIGN KEY FK_63337C31A77FBEAF');
        $this->addSql('ALTER TABLE blog_post_list_entry DROP FOREIGN KEY FK_63337C31C45B7A22');
        $this->addSql('DROP TABLE blog_post_list_entry');
    }
}
