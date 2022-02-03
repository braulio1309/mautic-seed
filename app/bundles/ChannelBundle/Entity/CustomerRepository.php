<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ChannelBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

class CustomerRepository extends CommonRepository
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
    public function getCustomerList($bundle, $search = '', $limit = 10, $start = 0, $includeGlobal = true)
    {
        $q = $this->createQueryBuilder('c');
        $q->select('c');

        if (!empty($search)) {
            $q->andWhere($q->expr()->like('c.name', ':search'))
                ->setParameter('search', "{$search}%");
        }

        $q->orderBy('c.name');

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
