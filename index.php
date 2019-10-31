<?php
/**
 * The main file
 */

require_once(__DIR__ . '/MotleyFool/Article.php');
use MotleyFool\Article;

get_header();
?>

  <section id="primary" class="content-area">
    <main id="main" class="article-wrapper container-fluid">
      <div class="col">
        <h1>All Articles</h1>
        <?php
          $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
          $args = [
              'posts_per_page' => 10,
              'post_status' => 'publish',
              'post_type' => [ 'article' ],
              'orderby' => 'date',
              'paged' => $paged,
          ];

          $loop = new WP_Query($args);

          if ($loop->have_posts()) {
              echo '<ul class="list-group">';
              while ($loop->have_posts()) : $loop->the_post();
              $article = new Article($post);
              echo "<li class='list-group-item'><a href='{$article->getLink()}'>{$article->getTitle()} ({$article->getDisplayTicker()})</a></li>";
              endwhile;
              echo '</ul>';
              $total_pages = $loop->max_num_pages;

              if ($total_pages > 1) {
                  $current_page = max(1, get_query_var('paged'));
                  echo paginate_links(array(
                      'base' => get_pagenum_link(1) . '%_%',
                      'format' => '/page/%#%',
                      'current' => $current_page,
                      'total' => $total_pages,
                      'prev_text' => '« prev',
                      'next_text' => 'next »',
                  ));
              }
          }
          wp_reset_postdata();
        ?>
      </div>
    </main>
  </section>

<?php
get_footer();
