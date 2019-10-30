<?php
/**
 * The single article file
 *
 */
require_once(__DIR__ . '/MotleyFool/Article.php');
use MotleyFool\Article;

get_header();
?>
  <main id="main" class="site-main ">
    <section id="primary" class="article-wrapper container-fluid">
      <?php
        global $post;
        $article = new Article($post);

        $article_ticker = $article->getArticleTicker();

        echo "<h1>{$article->getTitle()} <a href='/company/$article_ticker/'>({$article->getDisplayTicker()})</a></h1><hr />";
        echo "<h5>{$article->getDate()} - {$article->getUserDisplayName()}</h5><hr />";

        echo "<div class='row'>";
        if ($article->getArticleType() === 'stock-recommendations' && $article_ticker) {
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
