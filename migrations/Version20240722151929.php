<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722151929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id UUID NOT NULL, text TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN answer.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN answer.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE result (id UUID NOT NULL, session_id UUID DEFAULT NULL, test_case_id UUID DEFAULT NULL, answer_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_136AC113613FECDF ON result (session_id)');
        $this->addSql('CREATE INDEX IDX_136AC1131351003D ON result (test_case_id)');
        $this->addSql('CREATE INDEX IDX_136AC113AA334807 ON result (answer_id)');
        $this->addSql('COMMENT ON COLUMN result.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN result.session_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN result.test_case_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN result.answer_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN result.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE session (id UUID NOT NULL, user_id UUID DEFAULT NULL, test_suite_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D044D5D4A76ED395 ON session (user_id)');
        $this->addSql('CREATE INDEX IDX_D044D5D4DA9FBE4E ON session (test_suite_id)');
        $this->addSql('COMMENT ON COLUMN session.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN session.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN session.test_suite_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN session.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE test_case (id UUID NOT NULL, test_suite_id UUID DEFAULT NULL, question TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7D71B3CBDA9FBE4E ON test_case (test_suite_id)');
        $this->addSql('COMMENT ON COLUMN test_case.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN test_case.test_suite_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN test_case.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE test_cases_answers (test_case_id UUID NOT NULL, answer_id UUID NOT NULL, PRIMARY KEY(test_case_id, answer_id))');
        $this->addSql('CREATE INDEX IDX_F1D59AFD1351003D ON test_cases_answers (test_case_id)');
        $this->addSql('CREATE INDEX IDX_F1D59AFDAA334807 ON test_cases_answers (answer_id)');
        $this->addSql('COMMENT ON COLUMN test_cases_answers.test_case_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN test_cases_answers.answer_id IS \'(DC2Type:ulid)\'');
        $this->addSql('CREATE TABLE test_suite (id UUID NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN test_suite.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN test_suite.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113613FECDF FOREIGN KEY (session_id) REFERENCES session (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1131351003D FOREIGN KEY (test_case_id) REFERENCES test_case (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113AA334807 FOREIGN KEY (answer_id) REFERENCES test_case (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4DA9FBE4E FOREIGN KEY (test_suite_id) REFERENCES test_suite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE test_case ADD CONSTRAINT FK_7D71B3CBDA9FBE4E FOREIGN KEY (test_suite_id) REFERENCES test_suite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE test_cases_answers ADD CONSTRAINT FK_F1D59AFD1351003D FOREIGN KEY (test_case_id) REFERENCES test_case (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE test_cases_answers ADD CONSTRAINT FK_F1D59AFDAA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE result DROP CONSTRAINT FK_136AC113613FECDF');
        $this->addSql('ALTER TABLE result DROP CONSTRAINT FK_136AC1131351003D');
        $this->addSql('ALTER TABLE result DROP CONSTRAINT FK_136AC113AA334807');
        $this->addSql('ALTER TABLE session DROP CONSTRAINT FK_D044D5D4A76ED395');
        $this->addSql('ALTER TABLE session DROP CONSTRAINT FK_D044D5D4DA9FBE4E');
        $this->addSql('ALTER TABLE test_case DROP CONSTRAINT FK_7D71B3CBDA9FBE4E');
        $this->addSql('ALTER TABLE test_cases_answers DROP CONSTRAINT FK_F1D59AFD1351003D');
        $this->addSql('ALTER TABLE test_cases_answers DROP CONSTRAINT FK_F1D59AFDAA334807');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE test_case');
        $this->addSql('DROP TABLE test_cases_answers');
        $this->addSql('DROP TABLE test_suite');
        $this->addSql('DROP TABLE "user"');
    }
}
