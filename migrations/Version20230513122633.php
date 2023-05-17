<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230513122633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fielpromesse (id INT AUTO_INCREMENT NOT NULL, promesse_id INT NOT NULL, utilisateur_id INT DEFAULT NULL, qte INT NOT NULL, nature VARCHAR(255) NOT NULL, motif VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_36982A85D09FD084 (promesse_id), INDEX IDX_36982A85FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fielpromesse ADD CONSTRAINT FK_36982A85D09FD084 FOREIGN KEY (promesse_id) REFERENCES promesse (id)');
        $this->addSql('ALTER TABLE fielpromesse ADD CONSTRAINT FK_36982A85FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur_groupe DROP FOREIGN KEY FK_6514B6AA7A45358C');
        $this->addSql('ALTER TABLE utilisateur_groupe DROP FOREIGN KEY FK_6514B6AAFB88E14F');
        $this->addSql('DROP TABLE utilisateur_groupe');
        $this->addSql('ALTER TABLE fieldon DROP FOREIGN KEY FK_7D62107ED09FD084');
        $this->addSql('DROP INDEX IDX_7D62107ED09FD084 ON fieldon');
        $this->addSql('ALTER TABLE fieldon DROP promesse_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utilisateur_groupe (utilisateur_id INT NOT NULL, groupe_id INT NOT NULL, INDEX IDX_6514B6AAFB88E14F (utilisateur_id), INDEX IDX_6514B6AA7A45358C (groupe_id), PRIMARY KEY(utilisateur_id, groupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE utilisateur_groupe ADD CONSTRAINT FK_6514B6AA7A45358C FOREIGN KEY (groupe_id) REFERENCES user_groupe (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_groupe ADD CONSTRAINT FK_6514B6AAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fielpromesse DROP FOREIGN KEY FK_36982A85D09FD084');
        $this->addSql('ALTER TABLE fielpromesse DROP FOREIGN KEY FK_36982A85FB88E14F');
        $this->addSql('DROP TABLE fielpromesse');
        $this->addSql('ALTER TABLE fieldon ADD promesse_id INT NOT NULL');
        $this->addSql('ALTER TABLE fieldon ADD CONSTRAINT FK_7D62107ED09FD084 FOREIGN KEY (promesse_id) REFERENCES promesse (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_7D62107ED09FD084 ON fieldon (promesse_id)');
    }
}
