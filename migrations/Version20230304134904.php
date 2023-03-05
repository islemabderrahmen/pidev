<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230304134904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_like (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, article_id INT DEFAULT NULL, value INT NOT NULL, INDEX IDX_1C21C7B2A76ED395 (user_id), INDEX IDX_1C21C7B27294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_like ADD CONSTRAINT FK_1C21C7B2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article_like ADD CONSTRAINT FK_1C21C7B27294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article DROP favoris, DROP likes, DROP dislikes');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_like DROP FOREIGN KEY FK_1C21C7B2A76ED395');
        $this->addSql('ALTER TABLE article_like DROP FOREIGN KEY FK_1C21C7B27294869C');
        $this->addSql('DROP TABLE article_like');
        $this->addSql('ALTER TABLE article ADD favoris VARCHAR(255) NOT NULL, ADD likes INT NOT NULL, ADD dislikes INT NOT NULL');
    }
}
