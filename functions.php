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
      'slug' => 'news-articles'
    ],
    'capability_type' => 'page',
    'supports' => [ 'title', 'editor', 'revisions' ],
  ]);

  register_taxonomy(
    'article-type',
    'article',
    [
        'label' => 'Article Type',
        'hierarchical' => true,
        'show_ui' => true,
        'rewrite' => [
          'slug' => 'articles',
        ],
        'show_admin_column' => true,
        'show_in_quick_edit' => true,
    ]
  );
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

    $selected_ticker = get_post_meta($post->ID, 'article_ticker', true);
    ?>
        <select name="article_ticker" id="article_ticker" class="postbox">
            <?
                foreach ($tickers as $ticker) {
                    $symbol = $ticker->symbol;
                    $value = strtolower($ticker->symbol);
                    $selected = $value === $selected_ticker ? 'selected' : '';
                    echo "<option value='{$value}' {$selected}>{$symbol} ({$ticker->name})</option>";
                }
            ?>
        </select>
    <?
}

function article_ticker_box()
{
    add_meta_box(
        'article_ticker',
        'Article Ticker Symbol',
        'article_ticker_box_html',
        'article',
        'side'
    );
}
add_action('add_meta_boxes', 'article_ticker_box');

function save_article_ticker($post_id)
{
    if (isset($_POST['article_ticker'])) {
        update_post_meta(
            $post_id,
            'article_ticker',
            $_POST['article_ticker']
        );
    }
}
add_action('save_post', 'save_article_ticker');
