<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230524160846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code DROP FOREIGN KEY FK_77153098FB88E14F');
        $this->addSql('ALTER TABLE utilisateur_groupe DROP FOREIGN KEY FK_6514B6AA7A45358C');
        $this->addSql('ALTER TABLE utilisateur_groupe DROP FOREIGN KEY FK_6514B6AAFB88E14F');
        $this->addSql('DROP TABLE code');
        $this->addSql('DROP TABLE utilisateur_groupe');
        $this->addSql('ALTER TABLE mission ADD dateretour DATETIME NOT NULL');
        $this->addSql('ALTER TABLE rapportmission DROP dateretour, CHANGE opportunite opportunite TEXT DEFAULT NULL, CHANGE difficulte difficulte TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE code (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, code VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, libelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_77153098FB88E14F (utilisateur_id), UNIQUE INDEX UNIQ_7715309877153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE utilisateur_groupe (utilisateur_id INT NOT NULL, groupe_id INT NOT NULL, INDEX IDX_6514B6AA7A45358C (groupe_id), INDEX IDX_6514B6AAFB88E14F (utilisateur_id), PRIMARY KEY(utilisateur_id, groupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE code ADD CONSTRAINT FK_77153098FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE utilisateur_groupe ADD CONSTRAINT FK_6514B6AA7A45358C FOREIGN KEY (groupe_id) REFERENCES user_groupe (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_groupe ADD CONSTRAINT FK_6514B6AAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission DROP dateretour');
        $this->addSql('ALTER TABLE rapportmission ADD dateretour DATETIME NOT NULL, CHANGE opportunite opportunite TEXT NOT NULL, CHANGE difficulte difficulte TEXT NOT NULL');
    }
}
