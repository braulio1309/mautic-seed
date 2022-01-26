<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ChannelBundle\Model;

use Mautic\CampaignBundle\EventCollector\EventCollector;
use Mautic\CampaignBundle\Membership\MembershipBuilder;
use Mautic\ChannelBundle\Entity\Product;
use Mautic\CoreBundle\Model\FormModel as CommonFormModel;
use Mautic\FormBundle\Model\FormModel;
use Mautic\LeadBundle\Model\ListModel;
use Mautic\LeadBundle\Tracker\ContactTracker;

class ProductModel extends CommonFormModel
{
    /**
     * @var ListModel
     */
    protected $leadListModel;

    /**
     * @var FormModel
     */
    protected $formModel;

    /**
     * @var EventCollector
     */
    private $eventCollector;

    /**
     * @var MembershipBuilder
     */
    private $membershipBuilder;

    /**
     * @var ContactTracker
     */
    private $contactTracker;

    public function __construct(
        ListModel $leadListModel,
        FormModel $formModel,
        EventCollector $eventCollector,
        MembershipBuilder $membershipBuilder,
        ContactTracker $contactTracker
    ) {
        $this->leadListModel     = $leadListModel;
        $this->formModel         = $formModel;
        $this->eventCollector    = $eventCollector;
        $this->membershipBuilder = $membershipBuilder;
        $this->contactTracker    = $contactTracker;
    }

    /**
     * Get a specific entity or generate a new one if id is empty.
     *
     * @param $id
     *
     * @return Product|null
     */
    public function getEntity($id = null)
    {
        if (null === $id) {
            return new Product();
        }

        return parent::getEntity($id);
    }

    /**
     * {@inheritdoc}
     *
     * @return \Mautic\ChannelBundle\Entity\ProductRepository
     */
    public function getRepository()
    {
        $repo = $this->em->getRepository('MauticChannelBundle:Product');

        return $repo;
    }
}
