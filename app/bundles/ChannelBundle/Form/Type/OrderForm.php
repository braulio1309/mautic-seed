<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ChannelBundle\Form\Type;

use Mautic\CoreBundle\Form\Type\FormButtonsType;
use Mautic\CoreBundle\Security\Permissions\CorePermissions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CampaignType.
 */
class OrderForm extends AbstractType
{
    /**
     * @var CorePermissions
     */
    private $security;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label'      => 'Title',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class'       => 'form-control',
                'placeholder' => 'Title',
            ],
        ]);

        $builder->add('subtotal_price', NumberType::class, [
            'label'      => 'Subtotal',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class'       => 'form-control',
                'placeholder' => 'Subtotal',
            ],
        ]);

        $builder->add('total_tax', NumberType::class, [
            'label'      => 'Tax',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class'       => 'form-control',
                'placeholder' => 'Tax',
            ],
            'required'   => false,
        ]);

        $builder->add('currency', TextType::class, [
            'label'      => 'currency',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class'       => 'form-control',
                'placeholder' => 'Currency',
            ],
            'required'   => false,
        ]);

        $builder->add('payment_method', TextType::class, [
            'label'      => 'Payment Method',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class'       => 'form-control',
                'placeholder' => 'Payment method',
            ],
            'required'   => false,
        ]);

        $builder->add('notes', TextareaType::class, [
            'label'      => 'Notes',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control editor'],
        ]);

        $builder->add(
            $builder->create(
                'customer_id',
                CustomerListType::class,
                [
                    'label'      => 'Customer',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class' => 'form-control',
                    ],
                    'required' => false,
                    'multiple' => false,
                ]
            )
        );

        $builder->add('sessionId', HiddenType::class, [
            'mapped' => false,
        ]);

        if (!empty($options['action'])) {
            $builder->setAction($options['action']);
        }

        $builder->add('buttons', FormButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Mautic\ChannelBundle\Entity\Order',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'product';
    }
}
