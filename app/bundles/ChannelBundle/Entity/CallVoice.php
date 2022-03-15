<?php

namespace Mautic\ChannelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;

class CallVoice
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
     * @var decimal|null
     */
    private $totalCost;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var decimal|null
     */
    private $mainCallDuration;

    /**
     * @var decimal|null
     */
    private $secondCallDuration;

    /**
     * @var decimal|null
     */
    private $mainCallCost;

    /**
     * @var decimal|null
     */
    private $secondCallCost;

    /**
     * @var \DateTime|null
     */
    private $firstCallStartDate;

    /**
     * @var int|null
     */
    private $attempt;

    /**
     * @var int|null
     */
    private $maxAttempts;

    /**
     * @var string|null
     */
    private $callerId;

    /**
     * @var \DateTime|null
     */
    private $firstCallEndDate;

    /**
     * @var \DateTime|null
     */
    private $created_at;

    /**
     * @var \DateTime|null
     */
    private $updated_at;

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('call_voice')
            ->setCustomRepositoryClass('Mautic\ChannelBundle\Entity\CallVoiceRepository');
        $builder->addIdColumns();

        $builder->createField('totalCost', 'decimal')->nullable()->build();
        $builder->createField('mainCallDuration', 'decimal')->nullable()->build();
        $builder->createField('secondCallDuration', 'decimal')->nullable()->build();
        $builder->createField('mainCallCost', 'decimal')->nullable()->build();
        $builder->createField('secondCallCost', 'decimal')->nullable()->build();

        $builder->createField('firstCallStartDate', 'datetime')->nullable()->build();
        $builder->createField('firstCallEndDate', 'decimal')->nullable()->build();
        $builder->createField('callerId', 'string')->nullable()->build();

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMainCallDuration(): ?string
    {
        return $this->mainCallDuration;
    }

    public function setMainCallDuration($mainCallDuration): self
    {
        $this->mainCallDuration = $mainCallDuration;

        return $this;
    }

    public function getSecondCallDuration()
    {
        return $this->secondCallDuration;
    }

    public function setSecondCallDuration($secondCallDuration): self
    {
        $this->secondCallDuration = $secondCallDuration;

        return $this;
    }

    public function getMainCallCost()
    {
        return $this->secondCallCost;
    }

    public function setMainCallCost($mainCallCost): self
    {
        $this->mainCallCost = $mainCallCost;

        return $this;
    }

    public function getSecondCallCost()
    {
        return $this->secondCallCost;
    }

    public function setSecondCallCost($secondCallCost): self
    {
        $this->secondCallCost = $secondCallCost;

        return $this;
    }

    public function getTotalCost()
    {
        return $this->totalCost;
    }

    public function setTotalCost($totalCost): self
    {
        $this->totalCost = $totalCost;

        return $this;
    }

    public function getCallerId()
    {
        return $this->callerId;
    }

    public function setCallerId($callerId): self
    {
        $this->callerId = $callerId;

        return $this;
    }

    public function getFirstCallStartDate()
    {
        return $this->firstCallStartDate;
    }

    public function setFirstCallStartDate($firstCallStartDate): self
    {
        $this->firstCallStartDate = $firstCallStartDate;

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
