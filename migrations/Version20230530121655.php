<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230530121655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agenda (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, libelle VARCHAR(100) NOT NULL, description TINYTEXT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, all_day TINYINT(1) NOT NULL, background_color VARCHAR(255) DEFAULT NULL, border_color VARCHAR(255) DEFAULT NULL, text_color VARCHAR(255) DEFAULT NULL, INDEX IDX_2CEDC877FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE audience (id INT AUTO_INCREMENT NOT NULL, communaute_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, motif LONGTEXT NOT NULL, daterencontre DATETIME NOT NULL, nomchef VARCHAR(255) NOT NULL, numero VARCHAR(255) NOT NULL, email VARCHAR(60) DEFAULT NULL, nombreparticipant INT NOT NULL, observation TEXT DEFAULT NULL, statusaudience TINYINT(1) DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_FDCD9418C903E5B8 (communaute_id), INDEX IDX_FDCD9418FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE beneficiaire (id INT AUTO_INCREMENT NOT NULL, communaute_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, don_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, numero VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B140D802C903E5B8 (communaute_id), INDEX IDX_B140D802FB88E14F (utilisateur_id), INDEX IDX_B140D8027B3C9061 (don_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, code VARCHAR(20) NOT NULL, libelle VARCHAR(100) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_497DD634FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE communaute (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, localite_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, code VARCHAR(20) NOT NULL, libelle VARCHAR(255) NOT NULL, nbestmember INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_21C94799BCF5E72D (categorie_id), INDEX IDX_21C94799924DD2B5 (localite_id), INDEX IDX_21C94799FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, communaute_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, nom VARCHAR(60) NOT NULL, fonction VARCHAR(100) NOT NULL, email VARCHAR(60) DEFAULT NULL, numero VARCHAR(255) NOT NULL, observation TEXT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_4C62E638C903E5B8 (communaute_id), INDEX IDX_4C62E638FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, communaute_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, motif TEXT NOT NULL, daterencontre DATETIME NOT NULL, nom VARCHAR(255) NOT NULL, lieu_habitation VARCHAR(100) NOT NULL, numero VARCHAR(14) NOT NULL, statusdemande TINYINT(1) DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_2694D7A5C903E5B8 (communaute_id), INDEX IDX_2694D7A5FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE don (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, communaute_id INT NOT NULL, dateremise DATETIME NOT NULL, remispar VARCHAR(60) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, nom VARCHAR(255) NOT NULL, numero VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_F8F081D9FB88E14F (utilisateur_id), INDEX IDX_F8F081D9C903E5B8 (communaute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emailpf (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, communaute_id INT DEFAULT NULL, libelle VARCHAR(100) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_D8D7C913FB88E14F (utilisateur_id), INDEX IDX_D8D7C913C903E5B8 (communaute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, denomination VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fieldon (id INT AUTO_INCREMENT NOT NULL, typedon_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, don_id INT DEFAULT NULL, qte INT DEFAULT NULL, naturedon VARCHAR(255) DEFAULT NULL, motifdon VARCHAR(255) NOT NULL, montantdon DOUBLE PRECISION NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_7D62107EFF08FB0A (typedon_id), INDEX IDX_7D62107EFB88E14F (utilisateur_id), INDEX IDX_7D62107E7B3C9061 (don_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fielpromesse (id INT AUTO_INCREMENT NOT NULL, typedon_id INT NOT NULL, promesse_id INT NOT NULL, utilisateur_id INT DEFAULT NULL, qte INT NOT NULL, nature VARCHAR(255) NOT NULL, motif VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_36982A85FF08FB0A (typedon_id), INDEX IDX_36982A85D09FD084 (promesse_id), INDEX IDX_36982A85FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_module (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, ordre INT NOT NULL, lien VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE icon (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE localite (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, code VARCHAR(20) NOT NULL, libelle VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_F5D7E4A9FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission (id INT AUTO_INCREMENT NOT NULL, communaute_id INT NOT NULL, utilisateur_id INT NOT NULL, code VARCHAR(20) NOT NULL, ordremission VARCHAR(100) NOT NULL, chefmission VARCHAR(60) NOT NULL, objectif VARCHAR(200) NOT NULL, debut DATETIME NOT NULL, dateretour DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9067F23CC903E5B8 (communaute_id), INDEX IDX_9067F23CFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, ordre INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_groupe_permition (id INT AUTO_INCREMENT NOT NULL, permition_id INT DEFAULT NULL, module_id INT DEFAULT NULL, groupe_module_id INT DEFAULT NULL, groupe_user_id INT DEFAULT NULL, ordre INT NOT NULL, ordre_groupe INT NOT NULL, INDEX IDX_632E4EE3806F2303 (permition_id), INDEX IDX_632E4EE3AFC2B591 (module_id), INDEX IDX_632E4EE3FF5666A6 (groupe_module_id), INDEX IDX_632E4EE3610934DB (groupe_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nompf (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, communaute_id INT DEFAULT NULL, libelle VARCHAR(60) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_48843C8CFB88E14F (utilisateur_id), INDEX IDX_48843C8CC903E5B8 (communaute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE numeropf (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, communaute_id INT DEFAULT NULL, libelle VARCHAR(14) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_F2F82F44FB88E14F (utilisateur_id), INDEX IDX_F2F82F44C903E5B8 (communaute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_fichier (id VARCHAR(255) NOT NULL, size INT NOT NULL, path VARCHAR(255) NOT NULL, alt VARCHAR(255) NOT NULL, date DATETIME NOT NULL, url VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_service (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(10) NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parametre_configuration (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, logo_id VARCHAR(255) DEFAULT NULL, primary_color VARCHAR(255) NOT NULL, secondary_color VARCHAR(255) NOT NULL, INDEX IDX_1D3269A3A4AEAFEA (entreprise_id), INDEX IDX_1D3269A3F98F144A (logo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permition (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promesse (id INT AUTO_INCREMENT NOT NULL, communaute_id INT NOT NULL, utilisateur_id INT NOT NULL, nom VARCHAR(255) NOT NULL, numero VARCHAR(255) NOT NULL, email VARCHAR(60) DEFAULT NULL, dateremise DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, statusdon TINYINT(1) DEFAULT NULL, INDEX IDX_4900EF52C903E5B8 (communaute_id), INDEX IDX_4900EF52FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rapportmission (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, mission_id INT NOT NULL, dateretour DATETIME NOT NULL, action TEXT NOT NULL, opportunite TEXT DEFAULT NULL, difficulte TEXT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, prochaineetape VARCHAR(100) DEFAULT NULL, INDEX IDX_81FD875FB88E14F (utilisateur_id), INDEX IDX_81FD875BE6CAE90 (mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typedon (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, code VARCHAR(20) NOT NULL, libelle VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_5BFDC87A77153098 (code), INDEX IDX_5BFDC87AFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC877FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE audience ADD CONSTRAINT FK_FDCD9418C903E5B8 FOREIGN KEY (communaute_id) REFERENCES communaute (id)');
        $this->addSql('ALTER TABLE audience ADD CONSTRAINT FK_FDCD9418FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE beneficiaire ADD CONSTRAINT FK_B140D802C903E5B8 FOREIGN KEY (communaute_id) REFERENCES communaute (id)');
        $this->addSql('ALTER TABLE beneficiaire ADD CONSTRAINT FK_B140D802FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE beneficiaire ADD CONSTRAINT FK_B140D8027B3C9061 FOREIGN KEY (don_id) REFERENCES don (id)');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE communaute ADD CONSTRAINT FK_21C94799BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE communaute ADD CONSTRAINT FK_21C94799924DD2B5 FOREIGN KEY (localite_id) REFERENCES localite (id)');
        $this->addSql('ALTER TABLE communaute ADD CONSTRAINT FK_21C94799FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638C903E5B8 FOREIGN KEY (communaute_id) REFERENCES communaute (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5C903E5B8 FOREIGN KEY (communaute_id) REFERENCES communaute (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D9FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D9C903E5B8 FOREIGN KEY (communaute_id) REFERENCES communaute (id)');
        $this->addSql('ALTER TABLE emailpf ADD CONSTRAINT FK_D8D7C913FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE emailpf ADD CONSTRAINT FK_D8D7C913C903E5B8 FOREIGN KEY (communaute_id) REFERENCES communaute (id)');
        $this->addSql('ALTER TABLE fieldon ADD CONSTRAINT FK_7D62107EFF08FB0A FOREIGN KEY (typedon_id) REFERENCES typedon (id)');
        $this->addSql('ALTER TABLE fieldon ADD CONSTRAINT FK_7D62107EFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE fieldon ADD CONSTRAINT FK_7D62107E7B3C9061 FOREIGN KEY (don_id) REFERENCES don (id)');
        $this->addSql('ALTER TABLE fielpromesse ADD CONSTRAINT FK_36982A85FF08FB0A FOREIGN KEY (typedon_id) REFERENCES typedon (id)');
        $this->addSql('ALTER TABLE fielpromesse ADD CONSTRAINT FK_36982A85D09FD084 FOREIGN KEY (promesse_id) REFERENCES promesse (id)');
        $this->addSql('ALTER TABLE fielpromesse ADD CONSTRAINT FK_36982A85FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE localite ADD CONSTRAINT FK_F5D7E4A9FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CC903E5B8 FOREIGN KEY (communaute_id) REFERENCES communaute (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE module_groupe_permition ADD CONSTRAINT FK_632E4EE3806F2303 FOREIGN KEY (permition_id) REFERENCES permition (id)');
        $this->addSql('ALTER TABLE module_groupe_permition ADD CONSTRAINT FK_632E4EE3AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE module_groupe_permition ADD CONSTRAINT FK_632E4EE3FF5666A6 FOREIGN KEY (groupe_module_id) REFERENCES groupe_module (id)');
        $this->addSql('ALTER TABLE module_groupe_permition ADD CONSTRAINT FK_632E4EE3610934DB FOREIGN KEY (groupe_user_id) REFERENCES user_groupe (id)');
        $this->addSql('ALTER TABLE nompf ADD CONSTRAINT FK_48843C8CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE nompf ADD CONSTRAINT FK_48843C8CC903E5B8 FOREIGN KEY (communaute_id) REFERENCES communaute (id)');
        $this->addSql('ALTER TABLE numeropf ADD CONSTRAINT FK_F2F82F44FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE numeropf ADD CONSTRAINT FK_F2F82F44C903E5B8 FOREIGN KEY (communaute_id) REFERENCES communaute (id)');
        $this->addSql('ALTER TABLE parametre_configuration ADD CONSTRAINT FK_1D3269A3A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE parametre_configuration ADD CONSTRAINT FK_1D3269A3F98F144A FOREIGN KEY (logo_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE promesse ADD CONSTRAINT FK_4900EF52C903E5B8 FOREIGN KEY (communaute_id) REFERENCES communaute (id)');
        $this->addSql('ALTER TABLE promesse ADD CONSTRAINT FK_4900EF52FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE rapportmission ADD CONSTRAINT FK_81FD875FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE rapportmission ADD CONSTRAINT FK_81FD875BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE typedon ADD CONSTRAINT FK_5BFDC87AFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur_groupe DROP FOREIGN KEY FK_6514B6AA7A45358C');
        $this->addSql('ALTER TABLE utilisateur_groupe DROP FOREIGN KEY FK_6514B6AAFB88E14F');
        $this->addSql('DROP TABLE utilisateur_groupe');
        $this->addSql('ALTER TABLE employe ADD service_id INT DEFAULT NULL, ADD entreprise_id INT DEFAULT NULL, ADD matricule VARCHAR(12) NOT NULL');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9ED5CA9E6 FOREIGN KEY (service_id) REFERENCES param_service (id)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('CREATE INDEX IDX_F804D3B9ED5CA9E6 ON employe (service_id)');
        $this->addSql('CREATE INDEX IDX_F804D3B9A4AEAFEA ON employe (entreprise_id)');
        $this->addSql('ALTER TABLE param_fonction CHANGE libelle libelle VARCHAR(150) NOT NULL, CHANGE code code VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE user_utilisateur ADD groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_utilisateur ADD CONSTRAINT FK_B407AA267A45358C FOREIGN KEY (groupe_id) REFERENCES user_groupe (id)');
        $this->addSql('CREATE INDEX IDX_B407AA267A45358C ON user_utilisateur (groupe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9A4AEAFEA');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9ED5CA9E6');
        $this->addSql('CREATE TABLE utilisateur_groupe (utilisateur_id INT NOT NULL, groupe_id INT NOT NULL, INDEX IDX_6514B6AA7A45358C (groupe_id), INDEX IDX_6514B6AAFB88E14F (utilisateur_id), PRIMARY KEY(utilisateur_id, groupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE utilisateur_groupe ADD CONSTRAINT FK_6514B6AA7A45358C FOREIGN KEY (groupe_id) REFERENCES user_groupe (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_groupe ADD CONSTRAINT FK_6514B6AAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC877FB88E14F');
        $this->addSql('ALTER TABLE audience DROP FOREIGN KEY FK_FDCD9418C903E5B8');
        $this->addSql('ALTER TABLE audience DROP FOREIGN KEY FK_FDCD9418FB88E14F');
        $this->addSql('ALTER TABLE beneficiaire DROP FOREIGN KEY FK_B140D802C903E5B8');
        $this->addSql('ALTER TABLE beneficiaire DROP FOREIGN KEY FK_B140D802FB88E14F');
        $this->addSql('ALTER TABLE beneficiaire DROP FOREIGN KEY FK_B140D8027B3C9061');
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634FB88E14F');
        $this->addSql('ALTER TABLE communaute DROP FOREIGN KEY FK_21C94799BCF5E72D');
        $this->addSql('ALTER TABLE communaute DROP FOREIGN KEY FK_21C94799924DD2B5');
        $this->addSql('ALTER TABLE communaute DROP FOREIGN KEY FK_21C94799FB88E14F');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638C903E5B8');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638FB88E14F');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5C903E5B8');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5FB88E14F');
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D9FB88E14F');
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D9C903E5B8');
        $this->addSql('ALTER TABLE emailpf DROP FOREIGN KEY FK_D8D7C913FB88E14F');
        $this->addSql('ALTER TABLE emailpf DROP FOREIGN KEY FK_D8D7C913C903E5B8');
        $this->addSql('ALTER TABLE fieldon DROP FOREIGN KEY FK_7D62107EFF08FB0A');
        $this->addSql('ALTER TABLE fieldon DROP FOREIGN KEY FK_7D62107EFB88E14F');
        $this->addSql('ALTER TABLE fieldon DROP FOREIGN KEY FK_7D62107E7B3C9061');
        $this->addSql('ALTER TABLE fielpromesse DROP FOREIGN KEY FK_36982A85FF08FB0A');
        $this->addSql('ALTER TABLE fielpromesse DROP FOREIGN KEY FK_36982A85D09FD084');
        $this->addSql('ALTER TABLE fielpromesse DROP FOREIGN KEY FK_36982A85FB88E14F');
        $this->addSql('ALTER TABLE localite DROP FOREIGN KEY FK_F5D7E4A9FB88E14F');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23CC903E5B8');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23CFB88E14F');
        $this->addSql('ALTER TABLE module_groupe_permition DROP FOREIGN KEY FK_632E4EE3806F2303');
        $this->addSql('ALTER TABLE module_groupe_permition DROP FOREIGN KEY FK_632E4EE3AFC2B591');
        $this->addSql('ALTER TABLE module_groupe_permition DROP FOREIGN KEY FK_632E4EE3FF5666A6');
        $this->addSql('ALTER TABLE module_groupe_permition DROP FOREIGN KEY FK_632E4EE3610934DB');
        $this->addSql('ALTER TABLE nompf DROP FOREIGN KEY FK_48843C8CFB88E14F');
        $this->addSql('ALTER TABLE nompf DROP FOREIGN KEY FK_48843C8CC903E5B8');
        $this->addSql('ALTER TABLE numeropf DROP FOREIGN KEY FK_F2F82F44FB88E14F');
        $this->addSql('ALTER TABLE numeropf DROP FOREIGN KEY FK_F2F82F44C903E5B8');
        $this->addSql('ALTER TABLE parametre_configuration DROP FOREIGN KEY FK_1D3269A3A4AEAFEA');
        $this->addSql('ALTER TABLE parametre_configuration DROP FOREIGN KEY FK_1D3269A3F98F144A');
        $this->addSql('ALTER TABLE promesse DROP FOREIGN KEY FK_4900EF52C903E5B8');
        $this->addSql('ALTER TABLE promesse DROP FOREIGN KEY FK_4900EF52FB88E14F');
        $this->addSql('ALTER TABLE rapportmission DROP FOREIGN KEY FK_81FD875FB88E14F');
        $this->addSql('ALTER TABLE rapportmission DROP FOREIGN KEY FK_81FD875BE6CAE90');
        $this->addSql('ALTER TABLE typedon DROP FOREIGN KEY FK_5BFDC87AFB88E14F');
        $this->addSql('DROP TABLE agenda');
        $this->addSql('DROP TABLE audience');
        $this->addSql('DROP TABLE beneficiaire');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE communaute');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE don');
        $this->addSql('DROP TABLE emailpf');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE fieldon');
        $this->addSql('DROP TABLE fielpromesse');
        $this->addSql('DROP TABLE groupe_module');
        $this->addSql('DROP TABLE icon');
        $this->addSql('DROP TABLE localite');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE module_groupe_permition');
        $this->addSql('DROP TABLE nompf');
        $this->addSql('DROP TABLE numeropf');
        $this->addSql('DROP TABLE param_fichier');
        $this->addSql('DROP TABLE param_service');
        $this->addSql('DROP TABLE parametre_configuration');
        $this->addSql('DROP TABLE permition');
        $this->addSql('DROP TABLE promesse');
        $this->addSql('DROP TABLE rapportmission');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE typedon');
        $this->addSql('ALTER TABLE param_fonction CHANGE libelle libelle VARCHAR(50) NOT NULL, CHANGE code code VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE user_utilisateur DROP FOREIGN KEY FK_B407AA267A45358C');
        $this->addSql('DROP INDEX IDX_B407AA267A45358C ON user_utilisateur');
        $this->addSql('ALTER TABLE user_utilisateur DROP groupe_id');
        $this->addSql('DROP INDEX IDX_F804D3B9ED5CA9E6 ON employe');
        $this->addSql('DROP INDEX IDX_F804D3B9A4AEAFEA ON employe');
        $this->addSql('ALTER TABLE employe DROP service_id, DROP entreprise_id, DROP matricule');
    }
}
