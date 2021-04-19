<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419183322 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7F347EFB');
        $this->addSql('DROP INDEX IDX_BA388B7F347EFB ON cart');
        $this->addSql('DROP INDEX IDX_BA388B7A76ED395 ON cart');
        $this->addSql('ALTER TABLE cart ADD user_id_id INT DEFAULT NULL, ADD produit_id_id INT DEFAULT NULL, DROP user_id, DROP produit_id');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B79D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B74FD8F9C3 FOREIGN KEY (produit_id_id) REFERENCES produits (id)');
        $this->addSql('CREATE INDEX IDX_BA388B79D86650F ON cart (user_id_id)');
        $this->addSql('CREATE INDEX IDX_BA388B74FD8F9C3 ON cart (produit_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B79D86650F');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B74FD8F9C3');
        $this->addSql('DROP INDEX IDX_BA388B79D86650F ON cart');
        $this->addSql('DROP INDEX IDX_BA388B74FD8F9C3 ON cart');
        $this->addSql('ALTER TABLE cart ADD user_id INT DEFAULT NULL, ADD produit_id INT DEFAULT NULL, DROP user_id_id, DROP produit_id_id');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7F347EFB FOREIGN KEY (produit_id) REFERENCES produits (id)');
        $this->addSql('CREATE INDEX IDX_BA388B7F347EFB ON cart (produit_id)');
        $this->addSql('CREATE INDEX IDX_BA388B7A76ED395 ON cart (user_id)');
    }
}
