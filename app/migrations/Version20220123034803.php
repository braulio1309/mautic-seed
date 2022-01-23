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

final class Version20220123034803 extends AbstractMauticMigration
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
            'CREATE TABLE IF NOT EXISTS `customers` (
                `idcustomer` int(11) NOT NULL,
                `name` varchar(255) COLLATE utf16_spanish_ci NOT NULL,
                `lastname` varchar(255) COLLATE utf16_spanish_ci NOT NULL,
                `email` varchar(75) COLLATE utf16_spanish_ci NOT NULL,
                `phone` varchar(15) COLLATE utf16_spanish_ci NOT NULL,
                `created_at` datetime NOT NULL,
                `updated_at` datetime NOT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_spanish_ci;
              COMMIT;'
        );
    }
}
