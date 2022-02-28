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

use Mautic\ChannelBundle\Model\CategoryModel;
use Mautic\CoreBundle\Security\Permissions\CorePermissions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class CampaignListType.
 */
class CategoryListType extends AbstractType
{
    /**
     * @var CampaignModel
     */
    private $model;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var bool
     */
    private $canViewOther = false;

    public function __construct(CategoryModel $categoryModel, TranslatorInterface $translator, CorePermissions $security)
    {
        $this->model        = $categoryModel;
        $this->translator   = $translator;
        $this->canViewOther = $security->isGranted('campaign:campaigns:viewother');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'choices'      => function (Options $options) {
                    $choices   = [];
                    $categories = $this->model->getRepository()->getCategories();
                    foreach ($categories as $category) {
                        $choices[$category['name']] = $category['id'];
                    }

                    //sort by language
                    ksort($choices);

                    if ($options['include_this']) {
                        $choices = [$options['this_translation'] => 'this'] + $choices;
                    }

                    return $choices;
                },
                'placeholder'       => false,
                'expanded'          => false,
                'multiple'          => true,
                'required'          => false,
                'include_this'      => false,
                'this_translation'  => 'mautic.campaign.form.thiscampaign',
            ]
        );
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix()
    {
        return 'campaign_list';
    }
}
