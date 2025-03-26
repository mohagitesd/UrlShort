<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326081706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE short_link (id SERIAL NOT NULL, short_code VARCHAR(255) NOT NULL, url TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, max_visits INT DEFAULT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, valid_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN short_link.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN short_link.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN short_link.valid_on IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE short_link_tag (short_link_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(short_link_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_9754B0B5605D5D9 ON short_link_tag (short_link_id)');
        $this->addSql('CREATE INDEX IDX_9754B0B5BAD26311 ON short_link_tag (tag_id)');
        $this->addSql('CREATE TABLE tag (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, display_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_DISPLAY_NAME ON "user" (display_name)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE visit (id SERIAL NOT NULL, short_link_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ip VARCHAR(255) NOT NULL, user_agent TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_437EE939605D5D9 ON visit (short_link_id)');
        $this->addSql('COMMENT ON COLUMN visit.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE short_link_tag ADD CONSTRAINT FK_9754B0B5605D5D9 FOREIGN KEY (short_link_id) REFERENCES short_link (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE short_link_tag ADD CONSTRAINT FK_9754B0B5BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE939605D5D9 FOREIGN KEY (short_link_id) REFERENCES short_link (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE short_link_tag DROP CONSTRAINT FK_9754B0B5605D5D9');
        $this->addSql('ALTER TABLE short_link_tag DROP CONSTRAINT FK_9754B0B5BAD26311');
        $this->addSql('ALTER TABLE visit DROP CONSTRAINT FK_437EE939605D5D9');
        $this->addSql('DROP TABLE short_link');
        $this->addSql('DROP TABLE short_link_tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE visit');
    }
}
