<?php
/**
 * The Company Template File
 *
 * This is template file for /company/%ticker%/
 *
 */

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

              if ($company->profile) {
                  $last_dividend = $company->profile->lastDiv ?: 'N/A';
                  echo "<nav class='col-sm-3 p-4 col bg-light'>";
                  echo "<h4>{$company->symbol}({$company->profile->exchange})</h4>";
                  echo "<h6>Price - {$company->profile->price}</h6>";
                  echo "<h6>Changes - {$company->profile->changes} {$company->profile->changesPercentage}</h5>";
                  echo "<h6>Beta - {$company->profile->beta}</h6>";
                  echo "<h6>Volume Average - {$company->profile->volAvg}</h6>";
                  echo "<h6>Market Capitalization - {$company->profile->mktCap}</h6>";
                  echo "<h6>Last Dividend - {$last_dividend}</h6>";
                  echo "</nav>";

                  echo "<div class='col-sm-9 p-4 col'><img src='{$company->profile->image}' class='img-thumbnail rounded' alt='{$company->profile->companyName} Logo' />";
                  echo "<h1>{$company->profile->companyName} Articles</h1><hr />";
                  echo "<p>{$company->profile->description}</p>";

                  $recommendations = get_posts([
                  'numberposts' => -1,
                  'post_status' => 'publish',
                  'post_type' => [ 'article' ],
                  'orderby' => 'date',
                  'tax_query' => [
                      [
                          'taxonomy' => 'article-type',
                          'field' => 'slug',
                          'terms' => 'stock-recommendations',
                      ],
                      [
                          'taxonomy' => 'article-ticker',
                          'field' => 'slug',
                          'terms' => $term->slug,
                      ],
                  ],
                ]);

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
