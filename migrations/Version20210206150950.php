<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210206150950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926224584665A');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926226C8A81A9');
        $this->addSql('DROP INDEX IDX_D88926224584665A ON rating');
        $this->addSql('DROP INDEX IDX_D88926226C8A81A9 ON rating');
        $this->addSql('ALTER TABLE rating DROP product_id, DROP products_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating ADD product_id INT DEFAULT NULL, ADD products_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926224584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926226C8A81A9 FOREIGN KEY (products_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_D88926224584665A ON rating (product_id)');
        $this->addSql('CREATE INDEX IDX_D88926226C8A81A9 ON rating (products_id)');
    }
}
