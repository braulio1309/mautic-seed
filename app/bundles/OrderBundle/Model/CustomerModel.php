<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\OrderBundle\Model;

use Mautic\OrderBundle\Entity\Customer;
use Mautic\OrderBundle\Form\Type\CustomerForm;
use Mautic\CoreBundle\Model\FormModel as CommonFormModel;

class CustomerModel extends CommonFormModel
{
    /**
     * Get a specific entity or generate a new one if id is empty.
     *
     * @param $id
     *
     * @return Customer|null
     */
    public function getEntity($id = null)
    {
        if (null === $id) {
            return new Customer();
        }

        return parent::getEntity($id);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository|\Mautic\OrderBundle\Entity\CustomerRepository
     */
    public function getRepository()
    {
        $repo = $this->em->getRepository('OrderBundle:Customer');

        return $repo;
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
        if (!empty($action)) {
            $options['action'] = $action;
        }

        return $formFactory->create(CustomerForm::class, $entity, $options);
    }

    /**
     * Get list of entities for autopopulate fields.
     *
     * @param $bundle
     * @param $filter
     * @param $limit
     *
     * @return array
     */
    public function getLookupResults($bundle, $filter = '', $limit = 10)
    {
        static $results = [];

        $key = $bundle.$filter.$limit;
        if (!isset($results[$key])) {
            $results[$key] = $this->getRepository()->getCustomerList($bundle, $filter, $limit, 0);
        }

        return $results[$key];
    }
}
