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

use Mautic\ProductBundle\Entity\Variant;
use Mautic\ProductBundle\Form\Type\VariantForm;
use Mautic\CoreBundle\Model\FormModel as CommonFormModel;

class VariantModel extends CommonFormModel
{
    /**
     * Get a specific entity or generate a new one if id is empty.
     *
     * @param $id
     *
     * @return Variant|null
     */
    public function getEntity($id = null)
    {
        if (null === $id) {
            return new Variant();
        }

        return parent::getEntity($id);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository|\Mautic\ProductBundle\Entity\VariantRepository
     */
    public function getRepository()
    {
        $repo = $this->em->getRepository('ProductBundle:Variant');

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

        return $formFactory->create(VariantForm::class, $entity, $options);
    }
}
