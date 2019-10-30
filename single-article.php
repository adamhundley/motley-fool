<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 */

get_header();
?>
  <main id="main" class="site-main">
    <section id="primary" class="article">
      <?php
        global $post;

        $article_type = '';
        $article_type_terms = get_the_terms($post, 'article-type');
        if (isset($article_type_terms[0])) {
            $article_type = $article_type_terms[0]->slug;
        }

        $article_ticker = '';
        $article_ticker_terms = get_the_terms($post, 'article-ticker');
        if (isset($article_ticker_terms[0])) {
            $article_ticker = $article_ticker_terms[0]->slug;
        }
        echo $post->post_title;
        echo get_the_date('Y-m-d', $post);
        echo $article_ticker;
        $user = get_userdata($post->post_author);
        echo $user->data->display_name;
        echo $post->post_content;
        if ($article_type === 'stock-recommendations' && $article_ticker) {
            $api_url = "https://financialmodelingprep.com/api/v3/company/profile/{$article_ticker}";
            $response = wp_remote_get($api_url);

            $response_code = wp_remote_retrieve_response_code($response);
            if ($response_code === 200) {
                $company = json_decode(wp_remote_retrieve_body($response));
            } else {
                throw new Exception("");
            }

            if ($company->profile) {
                echo $company->profile->image;
                echo $company->profile->companyName;
                echo $company->profile->exchange;
                echo $company->profile->description;
                echo $company->profile->industry;
                echo $company->profile->sector;
                echo $company->profile->ceo;
                echo $company->profile->website;
            }
        }
      ?>
    </section>
  </main>
<?php
get_footer();
