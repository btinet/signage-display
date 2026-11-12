<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261112101713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE display_macro_prev_blog_post (display_macro_id INT NOT NULL, blog_post_id INT NOT NULL, INDEX IDX_11944EA093D0BD50 (display_macro_id), INDEX IDX_11944EA0A77FBEAF (blog_post_id), PRIMARY KEY(display_macro_id, blog_post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE display_macro_prev_shout_out (display_macro_id INT NOT NULL, shout_out_id INT NOT NULL, INDEX IDX_7861B30393D0BD50 (display_macro_id), INDEX IDX_7861B3031084C49C (shout_out_id), PRIMARY KEY(display_macro_id, shout_out_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE display_macro_prev_blog_post ADD CONSTRAINT FK_11944EA093D0BD50 FOREIGN KEY (display_macro_id) REFERENCES display_macro (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE display_macro_prev_blog_post ADD CONSTRAINT FK_11944EA0A77FBEAF FOREIGN KEY (blog_post_id) REFERENCES blog_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE display_macro_prev_shout_out ADD CONSTRAINT FK_7861B30393D0BD50 FOREIGN KEY (display_macro_id) REFERENCES display_macro (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE display_macro_prev_shout_out ADD CONSTRAINT FK_7861B3031084C49C FOREIGN KEY (shout_out_id) REFERENCES shout_out (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE display_macro ADD prev_show_schedule_column TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE display_macro_prev_blog_post DROP FOREIGN KEY FK_11944EA093D0BD50');
        $this->addSql('ALTER TABLE display_macro_prev_blog_post DROP FOREIGN KEY FK_11944EA0A77FBEAF');
        $this->addSql('ALTER TABLE display_macro_prev_shout_out DROP FOREIGN KEY FK_7861B30393D0BD50');
        $this->addSql('ALTER TABLE display_macro_prev_shout_out DROP FOREIGN KEY FK_7861B3031084C49C');
        $this->addSql('DROP TABLE display_macro_prev_blog_post');
        $this->addSql('DROP TABLE display_macro_prev_shout_out');
        $this->addSql('ALTER TABLE display_macro DROP prev_show_schedule_column');
    }
}
