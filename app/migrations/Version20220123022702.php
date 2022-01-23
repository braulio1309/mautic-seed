<?php

declare(strict_types=1);

/*
 * @copyright   2021 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        https://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\Exception\SkipMigration;
use Mautic\CoreBundle\Doctrine\AbstractMauticMigration;

final class Version20220123022702 extends AbstractMauticMigration
{
    /**
     * @throws SkipMigration
     */
    public function preUp(Schema $schema): void
    {
        $shouldRunMigration = true; // Please modify to your needs

        if (!$shouldRunMigration) {
            throw new SkipMigration('Schema includes this migration');
        }
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS `variants` (
              `idvariant` int(11) NOT NULL AUTO_INCREMENT,
              `name_variant` varchar(255) COLLATE utf16_spanish_ci NOT NULL,
              `value_variant` varchar(255) COLLATE utf16_spanish_ci NOT NULL,
              `quantity` int(11) DEFAULT NULL,
              `sku` varchar(255) COLLATE utf16_spanish_ci DEFAULT NULL,
              `price` double DEFAULT NULL,
              `taxable` tinyint(1) DEFAULT NULL,
              `barcode` varchar(255) COLLATE utf16_spanish_ci DEFAULT NULL,
              PRIMARY KEY (`idvariant`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_spanish_ci;
            COMMIT;'
        );
    }
}
