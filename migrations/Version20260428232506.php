<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260428232506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE score_entry (id INT AUTO_INCREMENT NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, field VARCHAR(50) NOT NULL, `column` VARCHAR(20) NOT NULL, value INT DEFAULT NULL, sheet_id INT NOT NULL, INDEX IDX_926D51F88B1206A5 (sheet_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE score_sheet (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, participant_id INT NOT NULL, INDEX IDX_3E705D6AE48FD905 (game_id), INDEX IDX_3E705D6A9D1C3019 (participant_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE score_entry ADD CONSTRAINT FK_926D51F88B1206A5 FOREIGN KEY (sheet_id) REFERENCES score_sheet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE score_sheet ADD CONSTRAINT FK_3E705D6AE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE score_sheet ADD CONSTRAINT FK_3E705D6A9D1C3019 FOREIGN KEY (participant_id) REFERENCES game_participant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE score_entry DROP FOREIGN KEY FK_926D51F88B1206A5');
        $this->addSql('ALTER TABLE score_sheet DROP FOREIGN KEY FK_3E705D6AE48FD905');
        $this->addSql('ALTER TABLE score_sheet DROP FOREIGN KEY FK_3E705D6A9D1C3019');
        $this->addSql('DROP TABLE score_entry');
        $this->addSql('DROP TABLE score_sheet');
    }
}
