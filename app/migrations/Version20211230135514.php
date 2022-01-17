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

final class Version20211230135514 extends AbstractMauticMigration
{
    /**
     * @throws SkipMigration
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
        "CREATE TABLE `{$this->prefix}sessions` (
          `sess_id` VARBINARY(128) NOT NULL PRIMARY KEY,
          `sess_data` BLOB NOT NULL,
          `sess_lifetime` INTEGER UNSIGNED NOT NULL,
          `sess_time` INTEGER UNSIGNED NOT NULL,
          INDEX `sessions_sess_lifetime_idx` (`sess_lifetime`)
      ) COLLATE utf8mb4_bin, ENGINE = InnoDB;"
    );
    }
}
