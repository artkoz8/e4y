<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240523071435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE course_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE course_leader_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE course (id INT NOT NULL, course_leader_id INT DEFAULT NULL, name VARCHAR(512) NOT NULL, date_of_training TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, price_amount INT NOT NULL, price_currency VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_169E6FB95E237E06 ON course (name)');
        $this->addSql('CREATE INDEX IDX_169E6FB9F1B40E78 ON course (course_leader_id)');
        $this->addSql('CREATE TABLE course_leader (id INT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9F1B40E78 FOREIGN KEY (course_leader_id) REFERENCES course_leader (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE course_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE course_leader_id_seq CASCADE');
        $this->addSql('ALTER TABLE course DROP CONSTRAINT FK_169E6FB9F1B40E78');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_leader');
    }
}
