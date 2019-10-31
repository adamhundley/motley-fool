<?php
namespace MotleyFool;

use stdClass;

class Site
{
    const TAXONOMIES = [
      'type' => [
          'slug' => 'article-type',
          'label' => 'Article Type',
          'rewrite-slug' => 'articles',
          'html' => 'articleTypeBoxHtml',
      ],
      'ticker' => [
          'slug' => 'article-ticker',
          'label' => 'Article Ticker',
          'rewrite-slug' => 'company',
          'html' => 'articleTickerBoxHtml',
      ],
    ];

    public function __construct()
    {
        add_action('init', [ __CLASS__, 'initSite' ]);
        add_action('save_post', [ __CLASS__, 'saveArticleTerms' ]);
        add_action('add_meta_boxes', [ __CLASS__, 'addArticleMetaBoxes' ]);
    }

    public function initSite(): void
    {
        self::registerArticlePostType();
        self::registerTaxonomies();
        self::createArticleTypes();
        if (!is_admin()) {
            wp_enqueue_style('bootstrap-styles', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
        }
    }

    public static function registerArticlePostType(): void
    {
        register_post_type('article', [
            'labels' => [
                'name' => 'Articles',
                'singular_name' => 'Article',
                'add_new' => 'Add New Article',
                'add_new_item' => 'Add Article',
                'edit_item' => 'Edit Article',
                'new_item' => 'New Article',
                'all_items' => 'All Articles',
                'view_item' => 'View Article',
                'search_items' => 'Search Articles',
                'not_found' => 'No articles found',
                'not_found_in_trash' => 'No articles found in Trash',
                'menu_name' => 'Articles'
            ],
            'label' => 'Article',
            'menu_icon' => 'dashicons-media-document',
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => [
              'with_front' => false,
              'pages' => false,
              'feed' => false,
              'slug' => 'article'
            ],
            'capability_type' => 'page',
            'supports' => [ 'title', 'editor', 'revisions' ],
        ]);
    }

    public static function registerTaxonomies(): void
    {
        foreach (self::TAXONOMIES as $taxonomy) {
            register_taxonomy(
                $taxonomy['slug'],
                'article',
                [
                    'label' => $taxonomy['label'],
                    'hierarchical' => true,
                    'show_ui' => false,
                    'rewrite' => [
                      'slug' => $taxonomy['rewrite-slug'],
                    ],
                ]
            );
        }
    }

    function articleTypeBoxHtml($post)
    {
        $article = new Article($post);
        $types = get_terms([
            'taxonomy' => 'article-type',
            'hide_empty' => false,
        ]);

        ?>
            <select name="article-type" id="article-type" class="postbox">
                <?
                    foreach ($types as $type) {
                        $selected = $type->slug === $article->getArticleType() ? 'selected' : '';
                        echo "<option value='{$type->slug}' {$selected}>{$type->name}</option>";
                    }
                ?>
            </select>
        <?
    }

    function articleTickerBoxHtml($post)
    {
        $api_url = "https://financialmodelingprep.com/api/v3/company/stock/list";
        $response = wp_remote_get($api_url);

        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code === 200) {
            $tickers = json_decode(wp_remote_retrieve_body($response))->symbolsList;
        } else {
            throw new Exception("");
        }

        $term_slug = '';
        $terms = get_the_terms($post, 'article-ticker');
        if (isset($terms[0])) {
            $term_slug = $terms[0]->slug;
        }
        ?>
            <select name="article-ticker" id="article-ticker" class="postbox">
                <?
                    foreach ($tickers as $ticker) {
                        $symbol = $ticker->symbol;
                        $value = strtolower($ticker->symbol);
                        $selected = $value === $term_slug ? 'selected' : '';
                        echo "<option value='{$value}' {$selected}>{$symbol} ({$ticker->name})</option>";
                    }
                ?>
            </select>
        <?
    }

    public function addArticleMetaBoxes(): void
    {
        foreach (self::TAXONOMIES as $taxonomy) {
            add_meta_box(
                $taxonomy['slug'],
                $taxonomy['label'],
                [ __CLASS__, $taxonomy['html'] ],
                'article',
                'side'
            );
        }
    }

    public static function createArticleTypes(): void
    {
        wp_insert_term('News', 'article-type');
        wp_insert_term('Stock Recommendations', 'article-type');
    }

    public static function saveArticleTerm($post_id, $term, $taxonomy): void
    {
        wp_set_object_terms(
            $post_id,
            $term,
            $taxonomy
        );
    }

    public function saveArticleTerms($post_id): void
    {
        $article_ticker = $_POST['article-ticker'] ?? '';
        $article_type = $_POST['article-type'] ?? '';

        if ($article_ticker) {
            $term = get_term_by('slug', $article_ticker);
            if (!$term) {
                wp_insert_term($article_ticker, 'article-ticker');
            }

            self::saveArticleTerm($post_id, $article_ticker, 'article-ticker');
        }

        if ($article_type) {
            self::saveArticleTerm($post_id, $article_type, 'article-type');
        }
    }
}
