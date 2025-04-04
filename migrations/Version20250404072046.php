<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250404072046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE avion ADD ref_modele_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avion ADD CONSTRAINT FK_234D9D38DF4EB64F FOREIGN KEY (ref_modele_id) REFERENCES modele (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_234D9D38DF4EB64F ON avion (ref_modele_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE conges CHANGE date_debut date_debut VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD ref_vol_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955EA329383 FOREIGN KEY (ref_vol_id) REFERENCES vol (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C84955EA329383 ON reservation (ref_vol_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol ADD ref_avion_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol ADD CONSTRAINT FK_95C97EB6AC7EC6 FOREIGN KEY (ref_avion_id) REFERENCES avion (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_95C97EB6AC7EC6 ON vol (ref_avion_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE avion DROP FOREIGN KEY FK_234D9D38DF4EB64F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_234D9D38DF4EB64F ON avion
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avion DROP ref_modele_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE conges CHANGE date_debut date_debut DATE NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955EA329383
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_42C84955EA329383 ON reservation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP ref_vol_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol DROP FOREIGN KEY FK_95C97EB6AC7EC6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_95C97EB6AC7EC6 ON vol
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol DROP ref_avion_id
        SQL);
    }
}
