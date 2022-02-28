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

final class Version20220123035012 extends AbstractMauticMigration
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
            'CREATE TABLE IF NOT EXISTS `address` (
                `idaddress` int(11) NOT NULL AUTO_INCREMENT,
                `customer_id` int(11) NOT NULL,
                `zip` varchar(15) COLLATE utf16_spanish_ci NOT NULL,
                `city` varchar(75) COLLATE utf16_spanish_ci NOT NULL,
                `country` varchar(75) COLLATE utf16_spanish_ci NOT NULL,
                `address1` varchar(255) COLLATE utf16_spanish_ci NOT NULL,
                `address2` varchar(255) COLLATE utf16_spanish_ci DEFAULT NULL,
                `province` varchar(100) COLLATE utf16_spanish_ci NOT NULL,
                `created_at` datetime NOT NULL,
                `updated_at` datetime NOT NULL,
                PRIMARY KEY (`idaddress`)
              ) ENGINE=InnoDB'
        );
    }
}
