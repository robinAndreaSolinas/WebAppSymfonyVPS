<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240314173340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication__param ADD CONSTRAINT FK_CA298158B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE publication__param ADD CONSTRAINT FK_CA298158896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE publication__param ADD CONSTRAINT FK_CA298158C76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CA298158B03A8386 ON publication__param (created_by_id)');
        $this->addSql('CREATE INDEX IDX_CA298158896DBBDE ON publication__param (updated_by_id)');
        $this->addSql('CREATE INDEX IDX_CA298158C76F1F52 ON publication__param (deleted_by_id)');
        $this->addSql('ALTER TABLE user CHANGE enabled enabled TINYINT(1) DEFAULT 1 NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE last_login last_login DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE enabled enabled TINYINT(1) NOT NULL, CHANGE created_at created_at DATETIME DEFAULT \'now()\' NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE last_login last_login DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE publication__param DROP FOREIGN KEY FK_CA298158B03A8386');
        $this->addSql('ALTER TABLE publication__param DROP FOREIGN KEY FK_CA298158896DBBDE');
        $this->addSql('ALTER TABLE publication__param DROP FOREIGN KEY FK_CA298158C76F1F52');
        $this->addSql('DROP INDEX IDX_CA298158B03A8386 ON publication__param');
        $this->addSql('DROP INDEX IDX_CA298158896DBBDE ON publication__param');
        $this->addSql('DROP INDEX IDX_CA298158C76F1F52 ON publication__param');
    }
}
