<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260702132418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, message LONGTEXT NOT NULL, gameParticipant_id INT NOT NULL, INDEX IDX_659DF2AA55CC9484 (gameParticipant_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA55CC9484 FOREIGN KEY (gameParticipant_id) REFERENCES game_participant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA55CC9484');
        $this->addSql('DROP TABLE chat');
    }
}
