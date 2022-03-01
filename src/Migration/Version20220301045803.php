<?php

declare(strict_types=1);

namespace Kadirov\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220301045803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add PaymeTransaction table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payme_transaction (id INT AUTO_INCREMENT NOT NULL, payme_id VARCHAR(255) DEFAULT NULL, time INT DEFAULT NULL, amount INT DEFAULT NULL, create_time BIGINT DEFAULT NULL, perform_time BIGINT DEFAULT NULL, cancel_time BIGINT DEFAULT NULL, state INT NOT NULL, reason INT DEFAULT NULL, custom_type INT NOT NULL, custom_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE payme_transaction');
    }
}
