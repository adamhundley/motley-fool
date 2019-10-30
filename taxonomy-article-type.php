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

require_once(__DIR__ . '/MotleyFool/Article.php');
require_once(__DIR__ . '/MotleyFool/ArticleType.php');
use MotleyFool\Article;
use MotleyFool\ArticleType;

get_header();
?>
  <main id="main" class="site-main">
    <section id="primary" class="article-wrapper container-fluid">
      <div class="row">
        <div class="col">
          <?php
            $term = get_queried_object();

            if ($term) {
                $article_type = new ArticleType($term);
                echo "<h1>{$article_type->getName()} Articles</h1><hr />";
                echo '<ul class="list-group">';
                foreach ($article_type->getArticles() as $article) {
                    echo "<li class='list-group-item'><a href='{$article->getLink()}'>{$article->getTitle()} ({$article->getDisplayTicker()})</a></li>";
                }
                echo "</ul>";
            }
          ?>
        </div>
      </div>
    </section>
  </main>
<?php
get_footer();
