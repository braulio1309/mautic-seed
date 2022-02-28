<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\LeadBundle\Model;

use Mautic\CoreBundle\Model\FormModel as CommonFormModel;
use Mautic\LeadBundle\Entity\LeadEventLog;

class LeadEventModel extends CommonFormModel
{
    /**
     * Get a specific entity or generate a new one if id is empty.
     *
     * @param $id
     *
     * @return LeadEvent|null
     */
    public function getEntity($id = null)
    {
        if (null === $id) {
            return new LeadEventLog();
        }

        return parent::getEntity($id);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository|\Mautic\LeadBundle\Entity\LeadEventLogRepository
     */
    public function getRepository()
    {
        $repo = $this->em->getRepository('MauticLeadBundle:LeadEventLog');

        return $repo;
    }
}
