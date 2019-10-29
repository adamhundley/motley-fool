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
        echo $post->post_title;
        echo get_the_date('Y-m-d', $post);
        echo get_post_meta($post->ID, 'article_ticker', true);
        $user = get_userdata($post->post_author);
        echo $user->data->display_name;
        echo $post->post_content;
      ?>
    </section>
  </main>
<?php
get_footer();
