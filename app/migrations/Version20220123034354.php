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

final class Version20220123034354 extends AbstractMauticMigration
{
    /**
     * @throws SkipMigration
     */
    public function preUp(Schema $schema): void
    {
        $shouldRunMigration = true;

        if (!$shouldRunMigration) {
            throw new SkipMigration('Schema includes this migration');
        }
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS `orders` (
                `idorder` int(11) NOT NULL AUTO_INCREMENT,
                `cancel_reason` varchar(255) COLLATE utf16_spanish_ci DEFAULT NULL,
                `cancelled_at` datetime DEFAULT NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `currency` varchar(20) COLLATE utf16_spanish_ci DEFAULT NULL,
                `total_discount` double DEFAULT NULL,
                `subtotal_price` double DEFAULT NULL,
                `total_tax` double DEFAULT NULL,
                `browser_ip` varchar(100) COLLATE utf16_spanish_ci DEFAULT NULL,
                `payment_method` varchar(100) COLLATE utf16_spanish_ci DEFAULT NULL,
                `notes` text COLLATE utf16_spanish_ci,
                `billing_address_id` int(11) DEFAULT NULL,
                `shipping_address_id` int(11) DEFAULT NULL,
                `customer_id` int(11) DEFAULT NULL,
                `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`idorder`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_spanish_ci;
              COMMIT;'
        );
    }
}
