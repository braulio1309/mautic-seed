<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ChannelBundle\Controller\Api;

use Mautic\ApiBundle\Controller\CommonApiController;
use Mautic\CampaignBundle\Membership\MembershipManager;
use Mautic\ChannelBundle\Entity\Product;
use Mautic\LeadBundle\Controller\LeadAccessTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class CallMessageApiController extends CommonApiController
{
    use LeadAccessTrait;

    /**
     * @var MembershipManager
     */
    private $membershipManager;

    public function initialize(FilterControllerEvent $event)
    {
        $this->model             = $this->getModel('channel.callVoice');
        $this->membershipManager = $this->get('mautic.campaign.membership.manager');
        $this->entityClass       = Product::class;
        $this->entityNameOne     = 'product';
        $this->entityNameMulti   = 'products';
        $this->permissionBase    = 'campaign:campaigns';
        $this->serializerGroups  = ['campaignDetails', 'campaignEventDetails', 'categoryList', 'publishDetails', 'leadListList', 'formList'];

        parent::initialize($event);
    }

    /**
     * GET
     * Get All products.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllAction($objectId = null)
    {
        $products =  ($objectId > 0) ? $this->model->getEntity($objectId) : $this->model->getEntities();
        $items    = [];
        if (!$objectId) {
            foreach ($products as $pro) {
                array_push($items, $pro);
            }
        } else {
            $items = $products;
        }

        $view = $this->view(
            [
                'success' => 1,
                'items'   => $items,
            ],
            Response::HTTP_OK
        );

        return $this->handleView($view);
    }

    /**
     * POST
     * Create products.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction($objectId = null)
    {
        $items      = $this->get('request_stack')->getCurrentRequest()->request;
        dd($items);
        $id         = (0 == $objectId) ? null : $objectId;
        $newProduct = $this->model->getEntity($id);

        ($items['total_cost']) ? $newProduct->setTotalCost($items['total_cost']) : '';
        ($items['mainCallDuration']) ? $newProduct->setMainCallDuration($items['mainCallDuration']) : '';
        ($items['secondCallDuration']) ? $newProduct->setSecondCallDuration($items['secondCallDuration']) : '';
        ($items['mainCallCost']) ? $newProduct->setMainCallCost($items['mainCallCost']) : '';
        ($items['secondCallCost']) ? $newProduct->setSecondCallCost($items['secondCallCost']) : '';
        ($items['firstCallStartDate']) ? $newProduct->setFirstCallStartDate($items['firstCallStartDate']) : '';
        ($items['firstCallEndDate']) ? $newProduct->setFirstCallEndDate($items['firstCallEndDate']) : '';
        ($items['callerId']) ? $newProduct->setCallerId($items['callerId']) : '';

        ($objectId || 0 == $objectId) ? $newProduct->setCreatedAt(new \DateTime()) : '';
        ($objectId || 0 == $objectId) ? $newProduct->setUpdatedAt(new \DateTime()) : '';

        $this->model->saveEntity($newProduct);

        $view = $this->view(
            [
                'success' => 1,
                'item'    => $newProduct,
            ],
            Response::HTTP_OK
        );

        return $this->handleView($view);
    }
}
