<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414190206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_fruit (id INT AUTO_INCREMENT NOT NULL, fruit_id INT NOT NULL, INDEX IDX_1683F9B3BAC115F0 (fruit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fruit (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, family VARCHAR(255) NOT NULL, fruit_order VARCHAR(255) NOT NULL, genus VARCHAR(255) NOT NULL, calories INT DEFAULT 0 NOT NULL, fat DOUBLE PRECISION NOT NULL, sugar DOUBLE PRECISION NOT NULL, carbohydrates DOUBLE PRECISION NOT NULL, protein DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite_fruit ADD CONSTRAINT FK_1683F9B3BAC115F0 FOREIGN KEY (fruit_id) REFERENCES fruit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_fruit DROP FOREIGN KEY FK_1683F9B3BAC115F0');
        $this->addSql('DROP TABLE favorite_fruit');
        $this->addSql('DROP TABLE fruit');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
