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
          $posts = get_posts([
              'numberposts' => -1,
              'post_status' => 'publish',
              'post_type' => [ 'article' ],
              'orderby' => 'date',
          ]);

          foreach ($posts as $post) {
              $article = new Article($post);
              echo "<li class='list-group-item'><a href='{$article->getLink()}'>{$article->getTitle()} ({$article->getDisplayTicker()})</a></li>";
          }
        ?>
      </div>
    </main>
  </section>

<?php
get_footer();
