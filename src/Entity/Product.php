<?php

namespace MartenaSoft\WarehouseProduct\Entity;

use MartenaSoft\WarehouseCommon\Entity\Interfaces\SavedStatusInterface;
use MartenaSoft\WarehouseCommon\Entity\Traits\DescriptionTrait;
use MartenaSoft\WarehouseCommon\Entity\Traits\NameTrait;
use MartenaSoft\WarehouseCommon\Entity\Traits\SavedStatusTrait;
use MartenaSoft\WarehouseProduct\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(name="product",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="articul",
 *            columns={"articul"})
 *    }
 * )
 */
class Product implements SavedStatusInterface
{
    use NameTrait, DescriptionTrait, SavedStatusTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $boughtPrice;

    /**
     * @ORM\Column(type="float")
     */
    private $recommendedPrice;

    /**
     * @ORM\Column(type="float")
     */
    private $soldPricePercent;


    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateChange;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDelete;

    /**
     * @ORM\Column(type="integer")
     */
    private $length;

    /**
     * @ORM\ManyToOne(targetEntity=Box::class, inversedBy="products")
     */
    private $box;

    /**
     * @ORM\ManyToOne(targetEntity=ProductStatus::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=65)
     */
    private $articul;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoughtPrice(): ?float
    {
        return $this->boughtPrice;
    }

    public function setBoughtPrice(float $boughtPrice): self
    {
        $this->boughtPrice = $boughtPrice;

        return $this;
    }

    public function getRecommendedPrice(): ?float
    {
        return $this->recommendedPrice;
    }

    public function setRecommendedPrice(float $recommendedPrice): self
    {
        $this->recommendedPrice = $recommendedPrice;

        return $this;
    }

    public function getSoldPricePercent(): ?float
    {
        return $this->soldPricePercent;
    }

    public function setSoldPricePercent(float $soldPricePercent): self
    {
        $this->soldPricePercent = $soldPricePercent;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->dateCreate;
    }

    public function setDateCreate(\DateTimeInterface $dateCreate): self
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    public function getDateChange(): ?\DateTimeInterface
    {
        return $this->dateChange;
    }

    public function setDateChange(\DateTimeInterface $dateChange): self
    {
        $this->dateChange = $dateChange;

        return $this;
    }

    public function getDateDelete(): ?\DateTimeInterface
    {
        return $this->dateDelete;
    }

    public function setDateDelete(\DateTimeInterface $dateDelete): self
    {
        $this->dateDelete = $dateDelete;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getBox(): ?Box
    {
        return $this->box;
    }

    public function setBox(?Box $box): self
    {
        $this->box = $box;

        return $this;
    }

    public function getStatus(): ?ProductStatus
    {
        return $this->status;
    }

    public function setStatus(?ProductStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getArticul(): ?string
    {
        return $this->articul;
    }

    public function setArticul(string $articul): self
    {
        $this->articul = $articul;

        return $this;
    }

}
