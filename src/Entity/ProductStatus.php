<?php

namespace MartenaSoft\WarehouseProduct\Entity;

use MartenaSoft\WarehouseCommon\Entity\Traits\DescriptionTrait;
use MartenaSoft\WarehouseCommon\Entity\Traits\NameTrait;
use MartenaSoft\WarehouseProduct\Repository\ProductStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use MartenaSoft\WarehouseProduct\Entity\Product;

/**
 * @ORM\Entity(repositoryClass=ProductStatusRepository::class)
 */
class ProductStatus
{
    use NameTrait, DescriptionTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="status")
     */
    private $products;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $safeMoneyOperation;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setStatus($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getStatus() === $this) {
                $product->setStatus(null);
            }
        }

        return $this;
    }

    public function getSafeMoneyOperation(): ?int
    {
        return $this->safeMoneyOperation;
    }

    public function setSafeMoneyOperation(?int $safeMoneyOperation): self
    {
        $this->safeMoneyOperation = $safeMoneyOperation;

        return $this;
    }

}
