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

class CategoryRepository extends CommonRepository
{
    public function getEntities(array $args = [])
    {
        $q = $this
        ->createQueryBuilder('c')
        ->select('c');

        $args['qb'] = $q;

        return parent::getEntities($args);
    }

    /**
     * @param object $entity
     * @param bool   $flush
     */
    public function deleteEntity($entity, $flush = true)
    {
        // Null parents of associated events first
        $q = $this->getEntityManager()->getConnection()->createQueryBuilder();
        $q->update(MAUTIC_TABLE_PREFIX.'products')
            ->where('idproduct = '.$entity->getId())
            ->execute();

        parent::deleteEntity($entity, $flush);
    }

    /**
     * @return string
     */
    public function getTableAlias()
    {
        return 'c';
    }

    public function getCategories()
    {
        $q = $this
        ->createQueryBuilder('c')
        ->select('c');

        return $q->getQuery()->getArrayResult();
    }
}
