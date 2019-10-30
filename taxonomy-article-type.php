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
    <section id="primary" class="article-wrapper container-fluid">
      <div class="row">
        <?php
          $articles = get_posts([
            'numberposts' => 10,
            'post_status' => 'publish',
            'post_type' => [ 'article' ],
            'orderby' => 'date',
            'tax_query' => [
                [
                    'taxonomy' => 'article-type',
                    'field' => 'slug',
                    'terms' => 'stock-recommendations',
                ],
            ],
          ]);

          echo '<ul class="list-group">';
          foreach ($articles as $article) {
              $url = get_permalink($article);
              echo "<li class='list-group-item'><a href='{$url}'>{$article->post_title}</a></li>";
          }
          echo "</ul>";
        ?>
      </div>
    </section>
  </main>
<?php
get_footer();
