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

use Mautic\ChannelBundle\Entity\Category;
use Mautic\ChannelBundle\Form\Type\CategoryForm;
use Mautic\CoreBundle\Model\FormModel as CommonFormModel;

class CategoryModel extends CommonFormModel
{
    /**
     * Get a specific entity or generate a new one if id is empty.
     *
     * @param $id
     *
     * @return Category|null
     */
    public function getEntity($id = null)
    {
        if (null === $id) {
            return new Category();
        }

        return parent::getEntity($id);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository|\Mautic\ChannelBundle\Entity\CategoryRepository
     */
    public function getRepository()
    {
        $repo = $this->em->getRepository('MauticChannelBundle:Category');

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
        if (!$entity instanceof Category) {
            throw new MethodNotAllowedHttpException(['category']);
        }

        if (!empty($action)) {
            $options['action'] = $action;
        }

        return $formFactory->create(CategoryForm::class, $entity, $options);
    }
}
