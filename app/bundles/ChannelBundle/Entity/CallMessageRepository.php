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

class CallMessageRepository extends CommonRepository
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
     * @return string
     */
    public function getTableAlias()
    {
        return 'c';
    }

    public function getMessages()
    {
        $q = $this
        ->createQueryBuilder('c')
        ->select('c');

        return $q->getQuery()->getArrayResult();
    }
}
