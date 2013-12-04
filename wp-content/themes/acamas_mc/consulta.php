<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
/*
Template Name: Consulta
*/

$cat = get_cat_ID("Consulta");

?>

<?php get_header(); ?>

<div id="content" class="widecolumn">

<?php get_search_form(); ?>

<ul class="consulta-<?php print $cat ?> consulta">
<?php print wp_list_categories("title_li=&hide_empty=0&feed=0&child_of=$cat") ?>
</ul>

</div>

<?php get_footer(); ?>
