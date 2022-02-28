<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ProductBundle;

/**
 * Class ProductBundle
 * Events available for ProductBundle.
 */
final class ProductEvents
{
    /**
     * The mautic.category_pre_save event is thrown right before a category is persisted.
     *
     * The event listener receives a
     * Mautic\CategoryBundle\Event\CategoryEvent instance.
     *
     * @var string
     */
    const PRODUCT_SAVE = 'mautic.product_save';

    /**
     * The mautic.category_pre_delete event is thrown prior to when a category is deleted.
     *
     * The event listener receives a
     * Mautic\CategoryBundle\Event\CategoryEvent instance.
     *
     * @var string
     */
    const PRODUCT_DELETE = 'mautic.product_delete';

    /**
     * The mautic.category_on_bundle_list_build event is thrown when a list of bundles supporting categories is build.
     *
     * The event listener receives a
     * Mautic\CategoryBundle\Event\CategoryTypesEvent instance.
     *
     * @var string
     */
    const PRODUCT_LIST = 'mautic.product_list';
}
