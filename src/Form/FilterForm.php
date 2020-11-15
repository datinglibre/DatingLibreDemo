<?php

namespace App\Form;

use DatingLibre\AppBundle\Entity\Region;

class FilterForm
{
    private array $sexes;
    private array $relationships;
    private ?Region $region;
    private ?int $distance;
    private ?int $minAge;
    private ?int $maxAge;

    public function __construct()
    {
        $this->region = null;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): void
    {
        $this->region = $region;
    }

    public function getSexes(): array
    {
        return $this->sexes;
    }

    public function setSexes($sexes): void
    {
        $this->sexes = $sexes;
    }

    public function getRelationships(): array
    {
        return $this->relationships;
    }

    public function setRelationships($relationships): void
    {
        $this->relationships = $relationships;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(?int $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getMinAge(): ?int
    {
        return $this->minAge;
    }

    public function setMinAge(int $minAge): void
    {
        $this->minAge = $minAge;
    }

    public function getMaxAge(): ?int
    {
        return $this->maxAge;
    }

    public function setMaxAge(?int $maxAge): void
    {
        $this->maxAge= $maxAge;
    }
}
