<?php

namespace MartenaSoft\WarehouseProduct\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class ApplaySafe
{
    private $types;

    public function __construct()
    {
        $this->types = new ArrayCollection();
    }

    public function getTypes()
    {
        return $this->types;
    }

    public function setTypes($types): self
    {
        $this->types = $types;
        return $this;
    }
}