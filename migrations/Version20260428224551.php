<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260428224551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, status TINYINT NOT NULL, game_status VARCHAR(50) NOT NULL, min_players INT NOT NULL, max_players INT NOT NULL, dice_number INT NOT NULL, join_code VARCHAR(20) DEFAULT NULL, startedAt DATETIME DEFAULT NULL, finishedAt DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE game_participant (id INT AUTO_INCREMENT NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, status TINYINT NOT NULL, position INT DEFAULT NULL, joinedAt DATETIME DEFAULT NULL, game_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9CA2913E48FD905 (game_id), INDEX IDX_9CA2913A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE game_participant ADD CONSTRAINT FK_9CA2913E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_participant ADD CONSTRAINT FK_9CA2913A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_participant DROP FOREIGN KEY FK_9CA2913E48FD905');
        $this->addSql('ALTER TABLE game_participant DROP FOREIGN KEY FK_9CA2913A76ED395');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_participant');
    }
}
