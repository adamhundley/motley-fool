<?php
namespace MotleyFool;

use stdClass;
use MotleyFool\Article;

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

    public function getPrice(): string
    {
        return $this->getProfile()->price;
    }

    public function getChanges(): string
    {
        return "{$this->getProfile()->changes} {$this->getProfile()->changesPercentage}";
    }

    public function getBeta(): string
    {
        return $this->getProfile()->beta;
    }

    public function getVolAvg(): string
    {
        return $this->getProfile()->volAvg;
    }

    public function getMktCap(): string
    {
        return $this->getProfile()->mktCap;
    }

    public function getLastDividend(): string
    {
        return $this->getProfile()->lastDiv ?: 'N/A';
    }

    public function getRecommendations(): array
    {
        $recommendations = [];
        $articles = get_posts([
            'numberposts' => -1,
            'post_status' => 'publish',
            'post_type' => [ 'article' ],
            'orderby' => 'date',
            'tax_query' => [
                [
                    'taxonomy' => 'article-type',
                    'field' => 'slug',
                    'terms' => 'stock-recommendations',
                ],
                [
                    'taxonomy' => 'article-ticker',
                    'field' => 'slug',
                    'terms' => strtolower($this->getSymbol()),
                ],
            ],
        ]);

        foreach ($articles as $article) {
            $recommendations[] = new Article($article);
        }

        return $recommendations;
    }
}
