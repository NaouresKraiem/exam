<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221223214807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE piece ADD montage_id INT DEFAULT NULL, DROP fournisseur');
        $this->addSql('ALTER TABLE piece ADD CONSTRAINT FK_44CA0B23E44D83C3 FOREIGN KEY (montage_id) REFERENCES montage (id)');
        $this->addSql('CREATE INDEX IDX_44CA0B23E44D83C3 ON piece (montage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE piece DROP FOREIGN KEY FK_44CA0B23E44D83C3');
        $this->addSql('DROP INDEX IDX_44CA0B23E44D83C3 ON piece');
        $this->addSql('ALTER TABLE piece ADD fournisseur VARCHAR(255) NOT NULL, DROP montage_id');
    }
}
