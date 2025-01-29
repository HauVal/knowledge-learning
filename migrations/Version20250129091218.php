<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129091218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_cursus_purchase (user_id INT NOT NULL, cursus_id INT NOT NULL, INDEX IDX_4A2947CEA76ED395 (user_id), INDEX IDX_4A2947CE40AEF4B9 (cursus_id), PRIMARY KEY(user_id, cursus_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_lesson_purchase (user_id INT NOT NULL, lesson_id INT NOT NULL, INDEX IDX_3B8CF7ADA76ED395 (user_id), INDEX IDX_3B8CF7ADCDF80196 (lesson_id), PRIMARY KEY(user_id, lesson_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_cursus_purchase ADD CONSTRAINT FK_4A2947CEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cursus_purchase ADD CONSTRAINT FK_4A2947CE40AEF4B9 FOREIGN KEY (cursus_id) REFERENCES cursus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_lesson_purchase ADD CONSTRAINT FK_3B8CF7ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_lesson_purchase ADD CONSTRAINT FK_3B8CF7ADCDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_cursus_purchase DROP FOREIGN KEY FK_4A2947CEA76ED395');
        $this->addSql('ALTER TABLE user_cursus_purchase DROP FOREIGN KEY FK_4A2947CE40AEF4B9');
        $this->addSql('ALTER TABLE user_lesson_purchase DROP FOREIGN KEY FK_3B8CF7ADA76ED395');
        $this->addSql('ALTER TABLE user_lesson_purchase DROP FOREIGN KEY FK_3B8CF7ADCDF80196');
        $this->addSql('DROP TABLE user_cursus_purchase');
        $this->addSql('DROP TABLE user_lesson_purchase');
    }
}
