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

final class Version20220123023503 extends AbstractMauticMigration
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
            "CREATE TABLE IF NOT EXISTS `products` (
                `idproduct` int(11) NOT NULL AUTO_INCREMENT,
                `product_name` VARCHAR(255) NOT NULL,
                `product_desc` TEXT(255) DEFAULT NULL,
                `category_id` int(11) DEFAULT NULL,
                `subcategory_id` int(11) DEFAULT NULL,
                `vendor` varchar(100) COLLATE utf16_spanish_ci DEFAULT NULL,
                `tags` varchar(255) COLLATE utf16_spanish_ci DEFAULT NULL,
                `is_available` tinyint(1) DEFAULT '0',
                `product_gallery` json DEFAULT NULL,
                `variants_ids` json DEFAULT NULL,
                `currency` varchar(20) COLLATE utf16_spanish_ci DEFAULT NULL,
                `initial_price` double DEFAULT NULL,
                `initial_quantity` int(11) DEFAULT NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`idproduct`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_spanish_ci;
              COMMIT;"
        );
    }
}
