<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ProductBundle\Controller\Api;

use Mautic\ApiBundle\Controller\CommonApiController;
use Mautic\CampaignBundle\Membership\MembershipManager;
use Mautic\LeadBundle\Controller\LeadAccessTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class VariantApiController extends CommonApiController
{
    use LeadAccessTrait;

    /**
     * @var MembershipManager
     */
    private $membershipManager;

    public function initialize(FilterControllerEvent $event)
    {
        $this->model             = $this->getModel('channel.variant');
        $this->membershipManager = $this->get('mautic.campaign.membership.manager');
        $this->entityClass       = Category::class;
        $this->entityNameOne     = 'Variant';
        $this->entityNameMulti   = 'Variants';
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
                'items'   => 1,
            ],
            Response::HTTP_OK
        );

        return $this->handleView($view);
    }

    /**
     * POST
     * Create/edit category.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction($objectId = null)
    {
        $items      = $this->get('request_stack')->getCurrentRequest()->request->all();
        $id         = (0 == $objectId) ? null : $objectId;
        $newProduct = $this->model->getEntity($id);

        ($items['name']) ? $newProduct->setName($items['name']) : '';
        ($items['value_variant']) ? $newProduct->setValueVariant($items['value_variant']) : '';

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
