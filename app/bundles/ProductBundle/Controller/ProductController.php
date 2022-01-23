<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ProductBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;
use Mautic\CoreBundle\Factory\PageHelperFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractFormController
{
    /**
     * @param int $page
     *
     * @return JsonResponse|Response
     */
    public function indexAction($page = 1)
    {
        $this->setListFilters();

        /** @var PageHelperFactoryInterface $pageHelperFacotry */
        $pageHelperFactory = $this->get('mautic.page.helper.factory');
        $pageHelper        = $pageHelperFactory->make('mautic.company', $page);

        $limit      = $pageHelper->getLimit();
        $start      = $pageHelper->getStart();
        $search     = $this->request->get('search', $this->get('session')->get('mautic.company.filter', ''));
        $filter     = ['string' => $search, 'force' => []];

        $products = $this->getModel('lead.company')->getEntities(
            [
                'start'          => $start,
                'limit'          => $limit,
                'filter'         => $filter,
                'withTotalCount' => true,
            ]
        );

        $count     = $products['count'];
        $products  = $products['results'];

        if ($count && $count < ($start + 1)) {
            $lastPage  = $pageHelper->countPage($count);
            $returnUrl = $this->generateUrl('products_list', ['page' => $lastPage]);
            $pageHelper->rememberPage($lastPage);

            return $this->postActionRedirect(
                [
                    'returnUrl'       => $returnUrl,
                    'viewParameters'  => ['page' => $lastPage],
                    'contentTemplate' => 'MauticLeadBundle:Company:index',
                    'passthroughVars' => [
                        'activeLink'    => '#mautic_company_index',
                        'mauticContent' => 'company',
                    ],
                ]
            );
        }

        $pageHelper->rememberPage($page);

        $tmpl        = $this->request->isXmlHttpRequest() ? $this->request->get('tmpl', 'index') : 'index';
        $model       = $this->getModel('lead.company');
        $productsIds = array_keys($products);
        $leadCounts  = (!empty($companyIds)) ? $model->getRepository()->getLeadCount($productsIds) : [];

        return $this->delegateView(
            [
                'viewParameters' => [
                    'searchValue' => $search,
                    'leadCounts'  => $leadCounts,
                    'items'       => $products,
                    'page'        => $page,
                    'limit'       => $limit,
                    'tmpl'        => $tmpl,
                    'totalItems'  => $count,
                ],
                'contentTemplate' => 'DestinyProductBundle:Product:list.html.php',
                'passthroughVars' => [
                    'activeLink'    => '#product_list',
                    'mauticContent' => 'product',
                    'route'         => $this->generateUrl('product_list', ['page' => $page]),
                ],
            ]
        );
    }
}
