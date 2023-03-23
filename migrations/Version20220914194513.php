<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220914194513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE proj_groupe_specialite (id INT AUTO_INCREMENT NOT NULL, groupe_id INT NOT NULL, specialite_id INT NOT NULL, INDEX IDX_3E8184D97A45358C (groupe_id), INDEX IDX_3E8184D92195E0F0 (specialite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE proj_groupe_specialite ADD CONSTRAINT FK_3E8184D97A45358C FOREIGN KEY (groupe_id) REFERENCES proj_groupe (id)');
        $this->addSql('ALTER TABLE proj_groupe_specialite ADD CONSTRAINT FK_3E8184D92195E0F0 FOREIGN KEY (specialite_id) REFERENCES proj_specialite (id)');
        $this->addSql('ALTER TABLE proj_niveau ADD groupe_specialite_id INT NOT NULL');
        $this->addSql('ALTER TABLE proj_niveau ADD CONSTRAINT FK_8A688F2ABDE0B56B FOREIGN KEY (groupe_specialite_id) REFERENCES proj_groupe_specialite (id)');
        $this->addSql('CREATE INDEX IDX_8A688F2ABDE0B56B ON proj_niveau (groupe_specialite_id)');
        $this->addSql('ALTER TABLE proj_specialite DROP FOREIGN KEY FK_DE7298F4C18272');
        $this->addSql('DROP INDEX IDX_DE7298F4C18272 ON proj_specialite');
        $this->addSql('ALTER TABLE proj_specialite DROP projet_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE proj_niveau DROP FOREIGN KEY FK_8A688F2ABDE0B56B');
        $this->addSql('ALTER TABLE proj_groupe_specialite DROP FOREIGN KEY FK_3E8184D97A45358C');
        $this->addSql('ALTER TABLE proj_groupe_specialite DROP FOREIGN KEY FK_3E8184D92195E0F0');
        $this->addSql('DROP TABLE proj_groupe_specialite');
        $this->addSql('DROP INDEX IDX_8A688F2ABDE0B56B ON proj_niveau');
        $this->addSql('ALTER TABLE proj_niveau DROP groupe_specialite_id');
        $this->addSql('ALTER TABLE proj_specialite ADD projet_id INT NOT NULL');
        $this->addSql('ALTER TABLE proj_specialite ADD CONSTRAINT FK_DE7298F4C18272 FOREIGN KEY (projet_id) REFERENCES proj_projet (id)');
        $this->addSql('CREATE INDEX IDX_DE7298F4C18272 ON proj_specialite (projet_id)');
    }
}
