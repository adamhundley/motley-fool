<?php
namespace MotleyFool;

use stdClass;

class Company
{
    /**
     * @var stdClass
     */
    private $data;

    public function __construct($company)
    {
        $this->company = $company;
    }

    public function getProfile(): object
    {
        return $this->company->profile;
    }

    public function getSymbol(): string
    {
        return $this->company->symbol;
    }

    public function getLogo(): string
    {
        return $this->getProfile()->image;
    }

    public function getName(): string
    {
        return $this->getProfile()->companyName;
    }

    public function getSlug(): string
    {
        return "/company/{$this->getSymbol()}/";
    }

    public function getExchange(): string
    {
        return $this->getProfile()->exchange;
    }

    public function getDescription(): string
    {
        return $this->getProfile()->description;
    }

    public function getIndustry(): string
    {
        return $this->getProfile()->industry;
    }

    public function getSector(): string
    {
        return $this->getProfile()->sector;
    }

    public function getCeo(): string
    {
        return $this->getProfile()->ceo;
    }

    public function getWebsite(): string
    {
        return $this->getProfile()->website;
    }
}
