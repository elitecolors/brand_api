<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210407115636 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, user_name VARCHAR(255) NOT NULL, name_prefix VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, midle_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, date_birth DATE NOT NULL, time_birth TIME NOT NULL, age_birth VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', date_join DATE DEFAULT NULL, age_in_company VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', phone VARCHAR(255) DEFAULT NULL, place VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zip VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contact');
    }
}
