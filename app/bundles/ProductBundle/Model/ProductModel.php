<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ProductBundle\Model;

use Mautic\CampaignBundle\EventCollector\EventCollector;
use Mautic\CampaignBundle\Membership\MembershipBuilder;
use Mautic\ProductBundle\Entity\Product;
use Mautic\ProductBundle\Form\Type\ProductForm;
use Mautic\CoreBundle\Helper\Chart\ChartQuery;
use Mautic\CoreBundle\Helper\Chart\LineChart;
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
     * @return \Doctrine\ORM\EntityRepository|\Mautic\ProductBundle\Entity\ProductRepository
     */
    public function getRepository()
    {
        $repo = $this->em->getRepository('ProductBundle:Product');

        return $repo;
    }

    public function getAllProducts()
    {
        $products = $this->getRepository();

        return $products->getAllProducts();
    }

    /**
     * {@inheritdoc}
     *
     * @param object      $entity
     * @param object      $formFactory
     * @param string|null $action
     * @param array       $options
     *
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function createForm($entity, $formFactory, $action = null, $options = [])
    {
        if (!$entity instanceof Product) {
            throw new MethodNotAllowedHttpException(['product']);
        }

        if (!empty($action)) {
            $options['action'] = $action;
        }

        return $formFactory->create(ProductForm::class, $entity, $options);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getPermissionBase()
    {
        return 'Product:product';
    }

    /**
     * Get bar chart data of contacts.
     *
     * @param string    $unit          {@link php.net/manual/en/function.date.php#refsect1-function.date-parameters}
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @param string    $dateFormat
     * @param array     $filter
     * @param bool      $canViewOthers
     *
     * @return array
     */
    public function getLeadsLineChartData($unit, $dateFrom, $dateTo, $dateFormat = null, $filter = [], $canViewOthers = true)
    {
        $flag        = null;
        $topLists    = null;
        $allLeadsT   = $this->translator->trans('mautic.products.all.products');
        $identifiedT = $this->translator->trans('mautic.product.identified');
        $anonymousT  = $this->translator->trans('mautic.lead.lead.anonymous');

        if (isset($filter['flag'])) {
            $flag = $filter['flag'];
            unset($filter['flag']);
        }

        if (!$canViewOthers) {
            $filter['owner_id'] = $this->userHelper->getUser()->getId();
        }

        $chart                              = new LineChart($unit, $dateFrom, $dateTo, $dateFormat);
        $query                              = new ChartQuery($this->em->getConnection(), $dateFrom, $dateTo);

        if ('top' == $flag) {
            $topLists = $this->leadListModel->getTopLists(6, $dateFrom, $dateTo);
            if ($topLists) {
                foreach ($topLists as $list) {
                    $filter['leadlist_id'] = [
                        'value'            => $list['id'],
                        'list_column_name' => 't.id',
                    ];
                    $all = $query->fetchTimeData('leads', 'date_added', $filter);
                    $chart->setDataset($list['name'].': '.$allLeadsT, $all);
                }
            }
        } elseif ('topIdentifiedVsAnonymous' == $flag) {
            $topLists = $this->leadListModel->getTopLists(3, $dateFrom, $dateTo);
            if ($topLists) {
                foreach ($topLists as $list) {
                    $anonymousFilter['leadlist_id'] = [
                        'value'            => $list['id'],
                        'list_column_name' => 't.id',
                    ];
                    $identifiedFilter['leadlist_id'] = [
                        'value'            => $list['id'],
                        'list_column_name' => 't.id',
                    ];
                    $identified = $query->fetchTimeData('products', 'created_at', $identifiedFilter);
                    $anonymous  = $query->fetchTimeData('products', 'created_at', $anonymousFilter);
                    $chart->setDataset($list['name'].': '.$identifiedT, $identified);
                    $chart->setDataset($list['name'].': '.$anonymousT, $anonymous);
                }
            }
        } elseif ('identified' == $flag) {
            $identified = $query->fetchTimeData('products', 'created_at', $identifiedFilter);
            $chart->setDataset($identifiedT, $identified);
        } elseif ('anonymous' == $flag) {
            $anonymous = $query->fetchTimeData('products', 'created_at', $anonymousFilter);
            $chart->setDataset($anonymousT, $anonymous);
        } elseif ('identifiedVsAnonymous' == $flag) {
            $identified = $query->fetchTimeData('products', 'created_at', $identifiedFilter);
            $anonymous  = $query->fetchTimeData('products', 'created_at', $anonymousFilter);
            $chart->setDataset($identifiedT, $identified);
            $chart->setDataset($anonymousT, $anonymous);
        } else {
            $all = $query->fetchTimeData('products', 'created_at', $filter);
            $chart->setDataset($allLeadsT, $all);
        }

        return $chart->render();
    }
}
