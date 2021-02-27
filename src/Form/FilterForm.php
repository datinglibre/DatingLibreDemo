<?php

namespace App\Form;

use DatingLibre\AppBundle\Entity\Region;

class FilterForm
{
    private ?Region $region;
    private array $interests;
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

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
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

    public function setInterests(array $interests): void
    {
        $this->interests = $interests;
    }

    public function getInterests(): array
    {
        return $this->interests;
    }
}
