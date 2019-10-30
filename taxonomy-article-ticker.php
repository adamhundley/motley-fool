<?php
/**
 * The Company Template File
 *
 * This is template file for /company/%ticker%/
 *
 */

require_once(__DIR__ . '/MotleyFool/Company.php');
use MotleyFool\Company;

get_header();
?>
  <main id="main" class="site-main">
    <section id="primary" class="article-wrapper container-fluid">
      <div class="row">
        <?php
          $term = get_queried_object();
          if ($term) {
              $company_url = "https://financialmodelingprep.com/api/v3/company/profile/{$term->slug}";
              $company_response = wp_remote_get($company_url);

              $company_response_code = wp_remote_retrieve_response_code($company_response);
              if ($company_response_code === 200) {
                  $company = json_decode(wp_remote_retrieve_body($company_response));
              } else {
                  throw new Exception("");
              }

              if ($company) {
                  $company = new Company($company);
                  echo "<nav class='col-sm-3 p-4 col bg-light'>";
                  echo "<h4>{$company->getSymbol()}({$company->getExchange()})</h4>";
                  echo "<h6>Price - {$company->getPrice()}</h6>";
                  echo "<h6>Changes - {$company->getChanges()}</h5>";
                  echo "<h6>Beta - {$company->getBeta()}</h6>";
                  echo "<h6>Volume Average - {$company->getVolAvg()}</h6>";
                  echo "<h6>Market Capitalization - {$company->getMktCap()}</h6>";
                  echo "<h6>Last Dividend - {$company->getLastDividend()}</h6>";
                  echo "</nav>";

                  echo "<div class='col-sm-9 p-4 col'><img src='{$company->getLogo()}' class='img-thumbnail rounded' alt='{$company->getName()} Logo' />";
                  echo "<h1>{$company->getName()} Articles</h1><hr />";
                  echo "<p>{$company->getDescription()}</p>";

                  if ($recommendations) {
                      echo "<h3>Recommendations</h3><hr />";
                      echo '<ul class="list-group">';
                      foreach ($recommendations as $article) {
                          $url = get_permalink($article);
                          $article_ticker = '';
                          $article_ticker_terms = get_the_terms($article, 'article-ticker');
                          if (isset($article_ticker_terms[0])) {
                              $article_ticker = strtoupper($article_ticker_terms[0]->name);
                          }
                          echo "<li class='list-group-item'><a href='{$url}'>{$article->post_title} ({$article_ticker})</a></li>";
                      }
                      echo "</ul>";
                  }

                  $news = get_posts([
                  'numberposts' => -1,
                  'post_status' => 'publish',
                  'post_type' => [ 'article' ],
                  'orderby' => 'date',
                  'tax_query' => [
                      [
                          'taxonomy' => 'article-type',
                          'field' => 'slug',
                          'terms' => 'news',
                      ],
                      [
                          'taxonomy' => 'article-ticker',
                          'field' => 'slug',
                          'terms' => $term->slug,
                      ],
                  ],
                ]);

                  if ($news) {
                      echo "<h3>News</h3><hr />";
                      echo '<ul class="list-group">';
                      foreach ($news as $article) {
                          $url = get_permalink($article);
                          $article_ticker = '';
                          $article_ticker_terms = get_the_terms($article, 'article-ticker');
                          if (isset($article_ticker_terms[0])) {
                              $article_ticker = strtoupper($article_ticker_terms[0]->name);
                          }
                          echo "<li class='list-group-item'><a href='{$url}'>{$article->post_title} ({$article_ticker})</a></li>";
                      }
                      echo "</ul>";
                  }
                  echo "</div>";
              }
          }
        ?>
      </div>
    </section>
  </main>
<?php
get_footer();
