<?php

declare(strict_types=1);

namespace App\Form;


use DatingLibre\AppBundle\Entity\Attribute;
use DatingLibre\AppBundle\Entity\City;
use DatingLibre\AppBundle\Entity\Country;
use DatingLibre\AppBundle\Entity\Region;

class ProfileForm
{
    private $username;
    private $about;
    private $dob;
    private ?Attribute $sex;
    private array $sexes;
    private array $interests;
    private ?City $city;
    private ?Region $region;
    private ?Country $country;

    public function __construct()
    {
        $this->city = null;
        $this->region = null;
        $this->country = null;
        $this->sex = null;
    }

    public function setUsername($username): ProfileForm
    {
        $this->username = $username;
        return $this;
    }

    public function setAbout($about): ProfileForm
    {
        $this->about = $about;
        return $this;
    }

    public function setCity(?City $city): ProfileForm
    {
        $this->city = $city;
        return $this;
    }

    public function setRegion(?Region $region): ProfileForm
    {
        $this->region = $region;
        return $this;
    }

    public function setCountry(?Country $country): ProfileForm
    {
        $this->country = $country;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setDob($dob): self
    {
        $this->dob = $dob;
        return $this;
    }

    public function getDob()
    {
        return $this->dob;
    }

    public function getSex(): ?Attribute
    {
        return $this->sex;
    }

    public function setSex(?Attribute $sex): void
    {
        $this->sex = $sex;
    }

    public function getSexes(): array
    {
        return $this->sexes;
    }

    public function setSexes(array $sexes): void
    {
        $this->sexes = $sexes;
    }

    public function getInterests(): array
    {
        return $this->interests;
    }

    public function setInterests(array $interests): void
    {
        $this->interests = $interests;
    }
}
