<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211004193705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, related_figure_id INT NOT NULL, user_id INT NOT NULL, date DATETIME NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_9474526CAD014DAE (related_figure_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE figure (id INT AUTO_INCREMENT NOT NULL, autor_id INT NOT NULL, figure_group_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, INDEX IDX_2F57B37A14D45BBE (autor_id), INDEX IDX_2F57B37AFDE864F2 (figure_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE figure_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE figure_picture (id INT AUTO_INCREMENT NOT NULL, related_figure_id INT NOT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_1C84F60BAD014DAE (related_figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE figure_video (id INT AUTO_INCREMENT NOT NULL, related_figure_id INT NOT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_6EEA5C15AD014DAE (related_figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_picture (id INT AUTO_INCREMENT NOT NULL, related_user_id INT NOT NULL, link VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_93CF80D898771930 (related_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, mail_is_validate TINYINT(1) NOT NULL, registration_key VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CAD014DAE FOREIGN KEY (related_figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37A14D45BBE FOREIGN KEY (autor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37AFDE864F2 FOREIGN KEY (figure_group_id) REFERENCES figure_group (id)');
        $this->addSql('ALTER TABLE figure_picture ADD CONSTRAINT FK_1C84F60BAD014DAE FOREIGN KEY (related_figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE figure_video ADD CONSTRAINT FK_6EEA5C15AD014DAE FOREIGN KEY (related_figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE profil_picture ADD CONSTRAINT FK_93CF80D898771930 FOREIGN KEY (related_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CAD014DAE');
        $this->addSql('ALTER TABLE figure_picture DROP FOREIGN KEY FK_1C84F60BAD014DAE');
        $this->addSql('ALTER TABLE figure_video DROP FOREIGN KEY FK_6EEA5C15AD014DAE');
        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37AFDE864F2');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37A14D45BBE');
        $this->addSql('ALTER TABLE profil_picture DROP FOREIGN KEY FK_93CF80D898771930');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE figure');
        $this->addSql('DROP TABLE figure_group');
        $this->addSql('DROP TABLE figure_picture');
        $this->addSql('DROP TABLE figure_video');
        $this->addSql('DROP TABLE profil_picture');
        $this->addSql('DROP TABLE user');
    }
}
