<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260805184018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_habits (user_id INT NOT NULL, habit_id INT NOT NULL, INDEX IDX_C0133A07A76ED395 (user_id), INDEX IDX_C0133A07E7AEB3B2 (habit_id), PRIMARY KEY (user_id, habit_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE user_habits ADD CONSTRAINT FK_C0133A07A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_habits ADD CONSTRAINT FK_C0133A07E7AEB3B2 FOREIGN KEY (habit_id) REFERENCES habit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE habit DROP FOREIGN KEY `FK_44FE21727E3C61F9`');
        $this->addSql('DROP INDEX IDX_44FE21727E3C61F9 ON habit');
        $this->addSql('ALTER TABLE habit DROP owner_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_habits DROP FOREIGN KEY FK_C0133A07A76ED395');
        $this->addSql('ALTER TABLE user_habits DROP FOREIGN KEY FK_C0133A07E7AEB3B2');
        $this->addSql('DROP TABLE user_habits');
        $this->addSql('ALTER TABLE habit ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE habit ADD CONSTRAINT `FK_44FE21727E3C61F9` FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_44FE21727E3C61F9 ON habit (owner_id)');
    }
}
