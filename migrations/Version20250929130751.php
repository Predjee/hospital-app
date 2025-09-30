<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929130751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE department (id BINARY(16) NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE doctor (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE doctor_department (doctor_id BINARY(16) NOT NULL, department_id BINARY(16) NOT NULL, INDEX IDX_FA0AC81B87F4FB17 (doctor_id), INDEX IDX_FA0AC81BAE80F5DF (department_id), PRIMARY KEY (doctor_id, department_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE patient (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, birth_date DATETIME NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE treatment (id BINARY(16) NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, started_at DATETIME DEFAULT NULL, completed_at DATETIME DEFAULT NULL, patient_id BINARY(16) NOT NULL, INDEX IDX_98013C316B899279 (patient_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE doctor_department ADD CONSTRAINT FK_FA0AC81B87F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE doctor_department ADD CONSTRAINT FK_FA0AC81BAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE treatment ADD CONSTRAINT FK_98013C316B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doctor_department DROP FOREIGN KEY FK_FA0AC81B87F4FB17');
        $this->addSql('ALTER TABLE doctor_department DROP FOREIGN KEY FK_FA0AC81BAE80F5DF');
        $this->addSql('ALTER TABLE treatment DROP FOREIGN KEY FK_98013C316B899279');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('DROP TABLE doctor_department');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE treatment');
    }
}
