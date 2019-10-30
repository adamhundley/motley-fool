<?php
namespace MotleyFool;

use stdClass;

class Article
{
    /**
     * @var stdClass
     */
    private $data;

    public function __construct($post)
    {
        $this->post = $post;
    }

    public function getArticleType(): string
    {
        $article_type = '';
        $article_type_terms = get_the_terms($post, 'article-type');
        if (isset($article_type_terms[0])) {
            $article_type = $article_type_terms[0]->slug;
        }

        return $article_type;
    }

    public function getArticleTicker(): string
    {
        $article_ticker = '';
        $article_ticker_terms = get_the_terms($post, 'article-ticker');
        if (isset($article_ticker_terms[0])) {
            $article_ticker = $article_ticker_terms[0]->slug;
        }

        return $article_ticker;
    }

    public function getDisplayTicker(): string
    {
        return strtoupper($this->getArticleTicker());
    }

    public function getTitle(): string
    {
        return $this->post->post_title;
    }

    public function getDate(): string
    {
        return get_the_date('Y-m-d', $this->post);
    }

    public function getUser(): object
    {
        return get_userdata($this->post->post_author);
    }

    public function getUserDisplayName()
    {
        return $this->getUser()->data->display_name;
    }
}
