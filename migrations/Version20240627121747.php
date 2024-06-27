<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627121747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_post (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, gallery_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, active TINYINT(1) NOT NULL, featured_image VARCHAR(255) DEFAULT NULL, title_visible TINYINT(1) NOT NULL, content_visible TINYINT(1) NOT NULL, featured_image_visible TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_BA5AE01D989D9B62 (slug), INDEX IDX_BA5AE01D5DA0FB8 (template_id), INDEX IDX_BA5AE01D4E7AF8F (gallery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_post_template (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, template_file VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5B0B5C4E989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01D5DA0FB8 FOREIGN KEY (template_id) REFERENCES blog_post_template (id)');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01D4E7AF8F FOREIGN KEY (gallery_id) REFERENCES image_gallery (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D5DA0FB8');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D4E7AF8F');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('DROP TABLE blog_post_template');
    }
}
