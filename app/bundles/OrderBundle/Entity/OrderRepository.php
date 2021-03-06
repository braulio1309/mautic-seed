<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\OrderBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

class OrderRepository extends CommonRepository
{
    public function getEntities(array $args = [])
    {
        $q = $this
        ->createQueryBuilder('p')
        ->select('p');
        $args['qb'] = $q;

        return parent::getEntities($args);
    }

    /**
     * @return string
     */
    public function getTableAlias()
    {
        return 'c';
    }
}
