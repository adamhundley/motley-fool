<?php

add_action('init', function () {
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
      'slug' => 'articles'
    ],
    'capability_type' => 'page',
    'supports' => [ 'title', 'editor', 'revisions' ],
  ]);

  wp_enqueue_style('bootstrap-styles', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');

  register_taxonomy(
    'article-type',
    'article',
    [
        'label' => 'Article Type',
        'hierarchical' => true,
        'show_ui' => false,
        'rewrite' => [
          'slug' => 'article',
        ],
    ]
  );

  register_taxonomy(
    'article-ticker',
    'article',
    [
        'label' => 'Article Ticker',
        'hierarchical' => true,
        'show_ui' => false,
        'rewrite' => [
          'slug' => 'company',
        ],
    ]
  );

  wp_insert_term('News', 'article-type');
  wp_insert_term('Stock Recommendations', 'article-type');
});

function article_ticker_box_html($post)
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
        <select name="article_ticker" id="article_ticker" class="postbox">
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

function article_type_box_html($post)
{
    $types = get_terms([
        'taxonomy' => 'article-type',
        'hide_empty' => false,
    ]);

    $term_slug = '';
    $terms = get_the_terms($post, 'article-type');
    if (isset($terms[0])) {
        $term_slug = $terms[0]->slug;
    }

    ?>
        <select name="article_type" id="article_ticker" class="postbox">
            <?
                foreach ($types as $type) {
                    $selected = $type->slug === $term_slug ? 'selected' : '';
                    echo "<option value='{$type->slug}' {$selected}>{$type->name}</option>";
                }
            ?>
        </select>
    <?
}

function article_term_selectors()
{
    add_meta_box(
        'article_ticker',
        'Article Ticker Symbol',
        'article_ticker_box_html',
        'article',
        'side'
    );

    add_meta_box(
        'article_type',
        'Article Type',
        'article_type_box_html',
        'article',
        'side'
    );
}

function save_article_terms($post_id)
{
    $article_ticker = $_POST['article_ticker'] ?? '';
    $article_type = $_POST['article_type'] ?? '';

    if ($article_ticker) {
      $term = get_term_by('slug', $article_ticker);
      if (!$term) {
        wp_insert_term($article_ticker, 'article-ticker');
        wp_set_object_terms(
          $post_id,
          $article_ticker,
          'article-ticker',
        );
      }
    }

    if ($article_type) {
      $term = get_term_by('slug', $article_ticker);
      if (!$term) {
        wp_set_object_terms(
          $post_id,
          $article_type,
          'article-type',
        );
      }
    }
}

add_action('save_post', 'save_article_terms');
add_action('add_meta_boxes', 'article_term_selectors');
