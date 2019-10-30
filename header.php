<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Motley Food
 * @since 1.0.0
 */

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php echo wp_get_document_title(); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="page">
<hr />
<div id="header" role="banner">
  <div id="headerimg">
    <header><nav class="nav align-items-center">
      <a class="nav-link" href="/"><img class="logo" src="https://www.f5.com/content/dam/f5-com/page-assets-en/home-en/customers/case-studies/TMF%20logo%20-%20proposed.png" alt="Logo" /></a>
      <a class="nav-link" href="/articles/news/">News</a>
      <a class="nav-link" href="/articles/stock-recommendations/">Stock Recommendations</a>
    </nav></header>
  </div>
</div>
<hr />
