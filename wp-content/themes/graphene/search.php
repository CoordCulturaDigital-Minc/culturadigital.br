<?php
/**
 * @package WordPress
 * @subpackage khairul-syahir.com-v2_Theme
 */

get_header(); ?>

<?php if (isset($_GET['search_404'])) { get_template_part('search', '404'); } else { get_template_part('loop', 'search'); } ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>