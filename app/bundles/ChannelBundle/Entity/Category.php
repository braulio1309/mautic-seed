<?php

namespace Mautic\ChannelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;

class Category
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $category_name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string
     */
    private $category_desc;

    /**
     * @var int
     */
    private $subcategory_ids;

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('products_categories')
            ->setCustomRepositoryClass('Mautic\ChannelBundle\Entity\CategoryRepository');
        $builder->addIdColumns();

        $builder->createField('category_desc', 'string')->nullable()->build();
    }

    /**
     * Prepares the metadata for API usage.
     *
     * @param $metadata
     */
    public static function loadApiMetadata(ApiMetadataDriver $metadata)
    {
        $metadata->setGroupPrefix('products_categories')
            ->addListProperties(
                [
                    'id',
                    'category_desc',
                ]
            )
            ->build();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): ?self
    {
        $this->id = $id;

        return $this;
    }

    public function getCategoryName(): ?string
    {
        return $this->category_name;
    }

    public function setCategoryName(string $category_name): self
    {
        $this->category_name = $category_name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategoryDesc(): ?string
    {
        return $this->category_desc;
    }

    public function setCategoryDesc(string $category_desc): self
    {
        $this->category_desc = $category_desc;

        return $this;
    }

    public function getInitialPrice()
    {
        return $this->initial_price;
    }

    public function setInitialPrice($initial_price): self
    {
        $this->initial_price = $initial_price;

        return $this;
    }

    public function getInitialQuantity()
    {
        return $this->initial_quantity;
    }

    public function setInitialQuantity($precio): self
    {
        $this->initial_quantity = $precio;

        return $this;
    }

    public function getVendor()
    {
        return $this->vendor;
    }

    public function setVendor($vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }

    public function getSubCategoryIds()
    {
        return $this->vendor;
    }

    public function setSubCategoryIds($subcategory_id): self
    {
        $this->subcategory_id = $subcategory_id;

        return $this;
    }
}
