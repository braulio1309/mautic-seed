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
class ProductForm extends AbstractType
{
    /**
     * @var CorePermissions
     */
    private $security;

    public function __construct(CorePermissions $security)
    {
        $this->security   = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label'      => 'mautic.core.name',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class'       => 'form-control',
                'placeholder' => 'Name',
            ],
        ]);

        $builder->add('product_desc', TextareaType::class, [
            'label'      => 'mautic.core.description',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class' => 'form-control editor',
            ],
            'required'   => false,
        ]);

        $builder->add(
            $builder->create(
                'category_id',
                CategoryListType::class,
                [
                    'label'      => 'Category',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class' => 'form-control',
                    ],
                    'required' => false,
                    'multiple' => false,
                ]
            )
        );

        $builder->add('initial_quantity', NumberType::class, [
            'label'      => 'Quantity',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class'       => 'form-control',
                'placeholder' => 'Quantity',
            ],
        ]);

        $builder->add('initial_price', NumberType::class, [
            'label'      => 'Price',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class'       => 'form-control',
                'placeholder' => 'Price',
            ],
        ]);

        $builder->add('vendor', TextType::class, [
            'label'      => 'Vendor',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class'       => 'form-control',
                'placeholder' => 'Vendor',
            ],
        ]);

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
            'data_class' => 'Mautic\ChannelBundle\Entity\Product',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'product';
    }
}
