<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230520125122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_milestones (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(1000) NOT NULL, milestone_deadline DATETIME NOT NULL, INDEX IDX_51FAB6166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_milestones ADD CONSTRAINT FK_51FAB6166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE user ADD deletedAt DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_milestones DROP FOREIGN KEY FK_51FAB6166D1F9C');
        $this->addSql('DROP TABLE project_milestones');
        $this->addSql('ALTER TABLE `user` DROP deletedAt');
    }
}
