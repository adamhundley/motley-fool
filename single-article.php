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
  <main id="main" class="site-main ">
    <section id="primary" class="article-wrapper container-fluid">
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

        $user = get_userdata($post->post_author);
        $date = get_the_date('Y-m-d', $post);
        $display_ticker = strtoupper($article_ticker);

        echo "<h1>{$post->post_title} <a href='/company/$article_ticker/'>($display_ticker)</a></h1><hr />";
        echo "<h5>{$date} - {$user->data->display_name}</h5><hr />";

        echo "<div class='row'>";
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
                echo "<nav class='col-sm-3 p-4 col bg-light'>";
                echo "<div class='text-center'><img src='{$company->profile->image}' class='img-thumbnail rounded' alt='{$company->profile->companyName} Logo' /><hr /></div>";
                echo "<h4><a href='/company/$article_ticker/'>{$company->profile->companyName}</a></h4>";
                echo "<h5><a href='/company/$article_ticker/'>{$company->symbol}({$company->profile->exchange})</a></h5>";
                echo "<p>{$company->profile->description}</p>";
                echo "<h6>Industry - {$company->profile->industry}</h6>";
                echo "<h6>Sector - {$company->profile->sector}</h6>";
                echo "<h6>CEO - {$company->profile->ceo}</h6>";
                echo "<a href='{$company->profile->website}' target='_blank'>Visit Website</a>";
                echo "</nav>";
            }
        }
        echo "<div class='col-sm-9 p-4 col'>{$post->post_content}</div>";
        echo "</div>";
      ?>
    </section>
  </main>
<?php
get_footer();
