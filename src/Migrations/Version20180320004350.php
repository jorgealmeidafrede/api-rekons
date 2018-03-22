<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180320004350 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, email VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), UNIQUE INDEX UNIQ_1483A5E9D4E6F81 (address), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, description LONGTEXT NOT NULL, status TINYINT(1) DEFAULT \'0\' NOT NULL, code VARCHAR(10) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B3BA5A5A5E237E06 (name), UNIQUE INDEX UNIQ_B3BA5A5A77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, status TINYINT(1) DEFAULT \'1\' NOT NULL, date DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E52FFDEEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders_products (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_749C879C8D9F6D38 (order_id), INDEX IDX_749C879C4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879C8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879C4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEA76ED395');
        $this->addSql('ALTER TABLE orders_products DROP FOREIGN KEY FK_749C879C4584665A');
        $this->addSql('ALTER TABLE orders_products DROP FOREIGN KEY FK_749C879C8D9F6D38');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE orders_products');
    }
}
