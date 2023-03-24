<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230324094138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audience ADD statusaudience TINYINT(1) NOT NULL, ADD mentions TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE don ADD statusdon TINYINT(1) NOT NULL, ADD mentions TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audience DROP statusaudience, DROP mentions');
        $this->addSql('ALTER TABLE don DROP statusdon, DROP mentions');
    }
}
