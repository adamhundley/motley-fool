<?php
namespace MotleyFool;

use MotleyFool\Article;

class ArticleType
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

    public function getArticles(): array
    {
        $article_objects = [];
        $articles = get_posts([
            'numberposts' => -1,
            'post_status' => 'publish',
            'post_type' => [ 'article' ],
            'orderby' => 'date',
            'tax_query' => [
                [
                    'taxonomy' => 'article-type',
                    'field' => 'slug',
                    'terms' => $this->getSlug(),
                ],
            ],
        ]);

        foreach ($articles as $article) {
            $article_objects[] = new Article($article);
        }

        return $article_objects;
    }
}
