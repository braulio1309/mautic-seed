<?php

namespace Mautic\ChannelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;

class Order
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $cancel_reason;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var decimal
     */
    private $subtotal_price;

    /**
     * @var decimal
     */
    private $total_tax;

    /**
     * @var decimal
     */
    private $total;

    /**
     * @var int
     */
    private $customer_id;

    /**
     * @var \DateTime|null
     */
    private $created_at;

    /**
     * @var \DateTime|null
     */
    private $updated_at;

    /**
     * @var string
     */
    private $payment_method;

    /**
     * @var string
     */
    private $currency;

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('orders')
            ->setCustomRepositoryClass('Mautic\ChannelBundle\Entity\OrderRepository');

        $builder->addIdColumns();
        $builder->createField('cancel_reason', 'string')
        ->nullable()
        ->build();
        $builder->createField('subtotal_price', 'decimal')
        ->nullable()
        ->build();
        $builder->createField('customer_id', 'integer')
        ->nullable()
        ->build();

        $builder->createField('currency', 'string')
        ->nullable()
        ->build();

        $builder->createField('payment_method', 'string')
        ->nullable()
        ->build();
        $builder->createField('notes', 'string')
        ->nullable()
        ->build();
        $builder->createField('total_tax', 'decimal')
        ->nullable()
        ->build();
        $builder->createField('total', 'decimal')
        ->nullable()
        ->build();

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

    public function getCancelReason(): ?string
    {
        return $this->cancel_reason;
    }

    public function setCancelReason(string $cancel_reason): self
    {
        $this->cancel_reason = $cancel_reason;

        return $this;
    }

    public function getSubtotalPrice()
    {
        return $this->subtotal_price;
    }

    public function setSubtotalPrice($subtotal_price)
    {
        $this->subtotal_price = $subtotal_price;

        return $this;
    }

    public function getTotalTax(): ?string
    {
        return $this->total_tax;
    }

    public function setTotalTax($total_tax): self
    {
        $this->total_tax = $total_tax;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $payment_method): self
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customer_id;
    }

    public function setCustomerId($customer_id): self
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setNotes($notes): self
    {
        $this->notes = $notes;

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
