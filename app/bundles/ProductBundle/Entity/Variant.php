<?php

namespace Mautic\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;

class Variant
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $value_variant;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var int
     */
    private $quantity;

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('variants')
            ->setCustomRepositoryClass('Mautic\ProductBundle\Entity\VariantRepository');
        $builder->addIdColumns();

        $builder->createField('value_variant', 'string')
        ->nullable()
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

    public function getValueVariant(): ?string
    {
        return $this->value_variant;
    }

    public function setValueVariant(string $value_variant): self
    {
        $this->value_variant = $value_variant;

        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantityPrice($quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
