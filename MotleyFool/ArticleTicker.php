<?php
namespace MotleyFool;

use stdClass;

class ArticleTicker
{
    /**
     * @var stdClass
     */
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
