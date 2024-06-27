<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626182639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image_image_gallery (image_id INT NOT NULL, image_gallery_id INT NOT NULL, INDEX IDX_9635CDED3DA5256D (image_id), INDEX IDX_9635CDED6839B8B9 (image_gallery_id), PRIMARY KEY(image_id, image_gallery_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image_image_gallery ADD CONSTRAINT FK_9635CDED3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_image_gallery ADD CONSTRAINT FK_9635CDED6839B8B9 FOREIGN KEY (image_gallery_id) REFERENCES image_gallery (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_image_gallery DROP FOREIGN KEY FK_9635CDED3DA5256D');
        $this->addSql('ALTER TABLE image_image_gallery DROP FOREIGN KEY FK_9635CDED6839B8B9');
        $this->addSql('DROP TABLE image_image_gallery');
    }
}
