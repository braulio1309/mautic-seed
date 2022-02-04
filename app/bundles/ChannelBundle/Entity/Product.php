<?php

namespace Mautic\ChannelBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;

class Product
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $product_name;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $product_desc;

    /**
     * @var float
     */
    private $initial_price;

    /**
     * @var int
     */
    private $initial_quantity;

    /**
     * @var int
     */
    private $category_id;

    /**
     * @var int
     */
    private $subcategory_id;

    /**
     * @var string
     */
    private $vendor;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var json
     */
    private $product_gallery;

    /**
     * @var json
     */
    private $variant_ids;

    /**
     * @var string
     */
    private $tags;

    /**
     * @var \DateTime|null
     */
    private $created_at;

    /**
     * @var \DateTime|null
     */
    private $updated_at;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->leads  = new ArrayCollection();
        $this->lists  = new ArrayCollection();
        $this->forms  = new ArrayCollection();
    }

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('products')
            ->setCustomRepositoryClass('Mautic\ChannelBundle\Entity\ProductRepository');

        $builder->addIdColumns();
        $builder->createField('product_name', 'string')->nullable()->build();
        $builder->createField('product_desc', 'string')->nullable()->build();
        $builder->createField('category_id', 'integer')->nullable()->build();
        $builder->createField('vendor', 'string')->nullable()->build();
        $builder->createField('currency', 'string')->nullable()->build();
        $builder->createField('tags', 'string')->nullable()->build();
        $builder->createField('initial_price', 'decimal')->nullable()->build();
        $builder->createField('initial_quantity', 'integer')->nullable()->build();
        $builder->createField('variant_ids', 'json')->nullable()->build();
        $builder->addField('created_at', 'datetime');
        $builder->addField('updated_at', 'datetime');
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    public function setProductName(string $product_name): self
    {
        $this->product_name = $product_name;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getProductDesc(): ?string
    {
        return $this->product_desc;
    }

    public function setProductDesc(string $product_desc): self
    {
        $this->product_desc = $product_desc;

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

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function setCategoryId($category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
