<?php

namespace Mautic\ChannelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\CommonEntity;

class Product extends CommonEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     */
    private $product_name;

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
     * @var date
     */
    private $created_at;

    /**
     * @var date
     */
    private $updated_at;

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('products')
            ->setCustomRepositoryClass('Mautic\ChannelBundle\Entity\ProductRepository');

        // Helper functions
        //$builder->addIdColumns();
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
        return $this->product_name;
    }

    public function setProduct_name(string $name): self
    {
        $this->product_name = $name;

        return $this;
    }

    public function getProduct_des(): ?string
    {
        return $this->product_desc;
    }

    public function setProduct_desc(string $name): self
    {
        $this->product_desc = $name;

        return $this;
    }

    public function getInitial_price()
    {
        return $this->initial_price;
    }

    public function setInitial_price($precio): self
    {
        $this->initial_price = $precio;

        return $this;
    }

    public function getInitial_quantity()
    {
        return $this->initial_quantity;
    }

    public function setInitial_quantity($precio): self
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

    public function getCategory_id()
    {
        return $this->vendor;
    }

    public function setCategory_id($category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }
}
