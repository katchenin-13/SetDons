<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220915193125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prj_colonne (id INT AUTO_INCREMENT NOT NULL, groupe_id INT NOT NULL, libelle VARCHAR(150) NOT NULL, source VARCHAR(150) DEFAULT \'\' NOT NULL, type_donnee VARCHAR(50) NOT NULL, required TINYINT(1) NOT NULL, liste_valeur LONGTEXT NOT NULL, INDEX IDX_DAC6FFD07A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prj_element (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prj_groupe (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(150) NOT NULL, ordre SMALLINT NOT NULL, code VARCHAR(5) NOT NULL, UNIQUE INDEX UNIQ_EA5B0E1277153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prj_groupe_specialite (id INT AUTO_INCREMENT NOT NULL, groupe_id INT NOT NULL, specialite_id INT NOT NULL, INDEX IDX_8ED9A7027A45358C (groupe_id), INDEX IDX_8ED9A7022195E0F0 (specialite_id), UNIQUE INDEX un_group_specialite (specialite_id, groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prj_niveau (id INT AUTO_INCREMENT NOT NULL, groupe_specialite_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, ordre SMALLINT NOT NULL, INDEX IDX_A53D7158BDE0B56B (groupe_specialite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prj_projet (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_BEF71E9AFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prj_specialite (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, code VARCHAR(5) NOT NULL, UNIQUE INDEX UNIQ_CC033A7177153098 (code), INDEX IDX_CC033A71FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prj_colonne ADD CONSTRAINT FK_DAC6FFD07A45358C FOREIGN KEY (groupe_id) REFERENCES prj_groupe (id)');
        $this->addSql('ALTER TABLE prj_groupe_specialite ADD CONSTRAINT FK_8ED9A7027A45358C FOREIGN KEY (groupe_id) REFERENCES prj_groupe (id)');
        $this->addSql('ALTER TABLE prj_groupe_specialite ADD CONSTRAINT FK_8ED9A7022195E0F0 FOREIGN KEY (specialite_id) REFERENCES prj_specialite (id)');
        $this->addSql('ALTER TABLE prj_niveau ADD CONSTRAINT FK_A53D7158BDE0B56B FOREIGN KEY (groupe_specialite_id) REFERENCES prj_groupe_specialite (id)');
        $this->addSql('ALTER TABLE prj_projet ADD CONSTRAINT FK_BEF71E9AFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE prj_specialite ADD CONSTRAINT FK_CC033A71FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE proj_colonne DROP FOREIGN KEY FK_64E2BA3E7A45358C');
        $this->addSql('ALTER TABLE proj_groupe_specialite DROP FOREIGN KEY FK_3E8184D92195E0F0');
        $this->addSql('ALTER TABLE proj_groupe_specialite DROP FOREIGN KEY FK_3E8184D97A45358C');
        $this->addSql('ALTER TABLE proj_niveau DROP FOREIGN KEY FK_8A688F2ABDE0B56B');
        $this->addSql('ALTER TABLE proj_projet DROP FOREIGN KEY FK_91A2E0E8FB88E14F');
        $this->addSql('ALTER TABLE proj_specialite DROP FOREIGN KEY FK_DE7298F4FB88E14F');
        $this->addSql('DROP TABLE proj_colonne');
        $this->addSql('DROP TABLE proj_element');
        $this->addSql('DROP TABLE proj_groupe');
        $this->addSql('DROP TABLE proj_groupe_specialite');
        $this->addSql('DROP TABLE proj_niveau');
        $this->addSql('DROP TABLE proj_projet');
        $this->addSql('DROP TABLE proj_specialite');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE proj_colonne (id INT AUTO_INCREMENT NOT NULL, groupe_id INT NOT NULL, libelle VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, source VARCHAR(150) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_ci`, type_donnee VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, required TINYINT(1) NOT NULL, liste_valeur LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_64E2BA3E7A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE proj_element (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE proj_groupe (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ordre SMALLINT NOT NULL, code VARCHAR(5) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_C50EF06077153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE proj_groupe_specialite (id INT AUTO_INCREMENT NOT NULL, groupe_id INT NOT NULL, specialite_id INT NOT NULL, UNIQUE INDEX un_group_specialite (specialite_id, groupe_id), INDEX IDX_3E8184D92195E0F0 (specialite_id), INDEX IDX_3E8184D97A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE proj_niveau (id INT AUTO_INCREMENT NOT NULL, groupe_specialite_id INT NOT NULL, libelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ordre SMALLINT NOT NULL, INDEX IDX_8A688F2ABDE0B56B (groupe_specialite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE proj_projet (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, libelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_creation DATETIME NOT NULL, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_91A2E0E8FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE proj_specialite (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, libelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_creation DATETIME NOT NULL, code VARCHAR(5) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_DE7298F477153098 (code), INDEX IDX_DE7298F4FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE proj_colonne ADD CONSTRAINT FK_64E2BA3E7A45358C FOREIGN KEY (groupe_id) REFERENCES proj_groupe (id)');
        $this->addSql('ALTER TABLE proj_groupe_specialite ADD CONSTRAINT FK_3E8184D92195E0F0 FOREIGN KEY (specialite_id) REFERENCES proj_specialite (id)');
        $this->addSql('ALTER TABLE proj_groupe_specialite ADD CONSTRAINT FK_3E8184D97A45358C FOREIGN KEY (groupe_id) REFERENCES proj_groupe (id)');
        $this->addSql('ALTER TABLE proj_niveau ADD CONSTRAINT FK_8A688F2ABDE0B56B FOREIGN KEY (groupe_specialite_id) REFERENCES proj_groupe_specialite (id)');
        $this->addSql('ALTER TABLE proj_projet ADD CONSTRAINT FK_91A2E0E8FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE proj_specialite ADD CONSTRAINT FK_DE7298F4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE prj_colonne DROP FOREIGN KEY FK_DAC6FFD07A45358C');
        $this->addSql('ALTER TABLE prj_groupe_specialite DROP FOREIGN KEY FK_8ED9A7027A45358C');
        $this->addSql('ALTER TABLE prj_groupe_specialite DROP FOREIGN KEY FK_8ED9A7022195E0F0');
        $this->addSql('ALTER TABLE prj_niveau DROP FOREIGN KEY FK_A53D7158BDE0B56B');
        $this->addSql('ALTER TABLE prj_projet DROP FOREIGN KEY FK_BEF71E9AFB88E14F');
        $this->addSql('ALTER TABLE prj_specialite DROP FOREIGN KEY FK_CC033A71FB88E14F');
        $this->addSql('DROP TABLE prj_colonne');
        $this->addSql('DROP TABLE prj_element');
        $this->addSql('DROP TABLE prj_groupe');
        $this->addSql('DROP TABLE prj_groupe_specialite');
        $this->addSql('DROP TABLE prj_niveau');
        $this->addSql('DROP TABLE prj_projet');
        $this->addSql('DROP TABLE prj_specialite');
    }
}
