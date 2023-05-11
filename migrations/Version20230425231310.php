<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230425231310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_utilisateur_groupe DROP FOREIGN KEY FK_C95050517A45358C');
        $this->addSql('ALTER TABLE user_utilisateur_groupe DROP FOREIGN KEY FK_C9505051FB88E14F');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE bouking');
        $this->addSql('DROP TABLE user_utilisateur_groupe');
        $this->addSql('ALTER TABLE rapportmission DROP FOREIGN KEY FK_81FD875BE6CAE90');
        $this->addSql('DROP INDEX IDX_81FD875BE6CAE90 ON rapportmission');
        $this->addSql('ALTER TABLE rapportmission DROP mission_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, begin_at DATETIME NOT NULL, end_at DATETIME NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bouking (id INT AUTO_INCREMENT NOT NULL, begin_at DATETIME NOT NULL, end_at DATETIME NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_utilisateur_groupe (utilisateur_id INT NOT NULL, groupe_id INT NOT NULL, INDEX IDX_C9505051FB88E14F (utilisateur_id), INDEX IDX_C95050517A45358C (groupe_id), PRIMARY KEY(utilisateur_id, groupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_utilisateur_groupe ADD CONSTRAINT FK_C95050517A45358C FOREIGN KEY (groupe_id) REFERENCES user_groupe (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_utilisateur_groupe ADD CONSTRAINT FK_C9505051FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user_utilisateur (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rapportmission ADD mission_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rapportmission ADD CONSTRAINT FK_81FD875BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_81FD875BE6CAE90 ON rapportmission (mission_id)');
    }
}
