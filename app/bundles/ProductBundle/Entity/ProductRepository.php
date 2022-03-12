<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ProductBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

class ProductRepository extends CommonRepository
{
    public function getEntities(array $args = [])
    {
        $q = $this
            ->createQueryBuilder('c')
            ->select('c');

        $args['qb'] = $q;

        return parent::getEntities($args);
    }

    public function getProducts()
    {
        $q = $this
        ->createQueryBuilder('c')
        ->select('c');

        return $q->getQuery()->getArrayResult();
    }

    /**
     * @return string
     */
    public function getTableAlias()
    {
        return 'c';
    }
}
