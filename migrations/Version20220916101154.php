<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220916101154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, fonction_id INT NOT NULL, civilite_id INT NOT NULL, nom VARCHAR(25) NOT NULL, prenom VARCHAR(100) NOT NULL, contact VARCHAR(50) NOT NULL, adresse_mail VARCHAR(255) NOT NULL, INDEX IDX_F804D3B957889920 (fonction_id), INDEX IDX_F804D3B939194ABF (civilite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_article (id INT AUTO_INCREMENT NOT NULL, famille_id INT DEFAULT NULL, colisage_id INT DEFAULT NULL, designation VARCHAR(255) NOT NULL, reference VARCHAR(255) NOT NULL, INDEX IDX_2060C89C97A77B84 (famille_id), INDEX IDX_2060C89C576C710F (colisage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, code VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_civilite (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(15) NOT NULL, code VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_colisage (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, code VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_famille (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, code VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_fonction (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, code VARCHAR(5) NOT NULL, UNIQUE INDEX UNIQ_544A947977153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_fournisseur (id INT AUTO_INCREMENT NOT NULL, famille_id INT DEFAULT NULL, code VARCHAR(10) NOT NULL, denomination_sociale VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, INDEX IDX_DF53223997A77B84 (famille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_operation (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(10) NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_service (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(10) NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_vehicule (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, service_id INT DEFAULT NULL, immatriculation VARCHAR(255) NOT NULL, date_achat DATETIME NOT NULL, type_carburant VARCHAR(255) NOT NULL, code VARCHAR(10) NOT NULL, prix DOUBLE PRECISION NOT NULL, consommation VARCHAR(255) NOT NULL, INDEX IDX_7465BED9BCF5E72D (categorie_id), INDEX IDX_7465BED9ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_groupe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_utilisateur (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B407AA26F85E0677 (username), UNIQUE INDEX UNIQ_B407AA261B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_groupe (utilisateur_id INT NOT NULL, groupe_id INT NOT NULL, INDEX IDX_6514B6AAFB88E14F (utilisateur_id), INDEX IDX_6514B6AA7A45358C (groupe_id), PRIMARY KEY(utilisateur_id, groupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B957889920 FOREIGN KEY (fonction_id) REFERENCES param_fonction (id)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B939194ABF FOREIGN KEY (civilite_id) REFERENCES param_civilite (id)');
        $this->addSql('ALTER TABLE param_article ADD CONSTRAINT FK_2060C89C97A77B84 FOREIGN KEY (famille_id) REFERENCES param_famille (id)');
        $this->addSql('ALTER TABLE param_article ADD CONSTRAINT FK_2060C89C576C710F FOREIGN KEY (colisage_id) REFERENCES param_colisage (id)');
        $this->addSql('ALTER TABLE param_fournisseur ADD CONSTRAINT FK_DF53223997A77B84 FOREIGN KEY (famille_id) REFERENCES param_famille (id)');
        $this->addSql('ALTER TABLE param_vehicule ADD CONSTRAINT FK_7465BED9BCF5E72D FOREIGN KEY (categorie_id) REFERENCES param_categorie (id)');
        $this->addSql('ALTER TABLE param_vehicule ADD CONSTRAINT FK_7465BED9ED5CA9E6 FOREIGN KEY (service_id) REFERENCES param_service (id)');
        $this->addSql('ALTER TABLE user_utilisateur ADD CONSTRAINT FK_B407AA261B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE utilisateur_groupe ADD CONSTRAINT FK_6514B6AAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_groupe ADD CONSTRAINT FK_6514B6AA7A45358C FOREIGN KEY (groupe_id) REFERENCES user_groupe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B957889920');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B939194ABF');
        $this->addSql('ALTER TABLE param_article DROP FOREIGN KEY FK_2060C89C97A77B84');
        $this->addSql('ALTER TABLE param_article DROP FOREIGN KEY FK_2060C89C576C710F');
        $this->addSql('ALTER TABLE param_fournisseur DROP FOREIGN KEY FK_DF53223997A77B84');
        $this->addSql('ALTER TABLE param_vehicule DROP FOREIGN KEY FK_7465BED9BCF5E72D');
        $this->addSql('ALTER TABLE param_vehicule DROP FOREIGN KEY FK_7465BED9ED5CA9E6');
        $this->addSql('ALTER TABLE user_utilisateur DROP FOREIGN KEY FK_B407AA261B65292');
        $this->addSql('ALTER TABLE utilisateur_groupe DROP FOREIGN KEY FK_6514B6AAFB88E14F');
        $this->addSql('ALTER TABLE utilisateur_groupe DROP FOREIGN KEY FK_6514B6AA7A45358C');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE param_article');
        $this->addSql('DROP TABLE param_categorie');
        $this->addSql('DROP TABLE param_civilite');
        $this->addSql('DROP TABLE param_colisage');
        $this->addSql('DROP TABLE param_famille');
        $this->addSql('DROP TABLE param_fonction');
        $this->addSql('DROP TABLE param_fournisseur');
        $this->addSql('DROP TABLE param_operation');
        $this->addSql('DROP TABLE param_service');
        $this->addSql('DROP TABLE param_vehicule');
        $this->addSql('DROP TABLE user_groupe');
        $this->addSql('DROP TABLE user_utilisateur');
        $this->addSql('DROP TABLE utilisateur_groupe');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
