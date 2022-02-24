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

use Mautic\ChannelBundle\Entity\CallMessage;
use Mautic\ChannelBundle\Form\Type\CallMessageForm;
use Mautic\CoreBundle\Model\FormModel as CommonFormModel;

class CallMessageModel extends CommonFormModel
{
    /**
     * Get a specific entity or generate a new one if id is empty.
     *
     * @param $id
     *
     * @return CallMessage|null
     */
    public function getEntity($id = null)
    {
        if (null === $id) {
            return new CallMessage();
        }

        return parent::getEntity($id);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository|\Mautic\ChannelBundle\Entity\CallMessageRepository
     */
    public function getRepository()
    {
        $repo = $this->em->getRepository('MauticChannelBundle:CallMessage');

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
        if (!$entity instanceof CallMessage) {
            throw new MethodNotAllowedHttpException(['CallMessage']);
        }

        if (!empty($action)) {
            $options['action'] = $action;
        }

        return $formFactory->create(CallMessageForm::class, $entity, $options);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getPermissionBase()
    {
        return 'channel:call';
    }
}
