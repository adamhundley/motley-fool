<?php
namespace MotleyFool;

class ArticleTicker
{
    private $term;

    public function __construct($term)
    {
        $this->term = $term;
    }

    public function getName(): string
    {
        return $this->term->name;
    }

    public function getSlug(): string
    {
        return $this->term->slug;
    }
}
