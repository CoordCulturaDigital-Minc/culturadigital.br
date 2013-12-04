<?php 
global $shortname;

$number_posts = (get_option('posts_per_page')) ? get_option('posts_per_page') : 6;

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

if (is_active_widget('widget_myFeature')) {
	$category = "showposts=$posts&cat=-".$options['category'];		
} else {
	$category = "showposts=".$posts;		
} 
query_posts($category."&paged=$paged&showposts=$number_posts");

get_header();

	//Featured Content Gallery section on the homepage
	if (is_home() && !isset($_GET['paged'])) :?>
		<?php if(function_exists('gallery_styles')) :?>
            <div id="fcg-slides">
            	<?php include (ABSPATH . '/wp-content/plugins/featured-content-gallery/gallery.php'); ?>
            </div>
        <?php elseif(isset($_GET['preview']) && isset($_GET['template'])):?>
            <div id="fcg-slides">
            	<img src="<?php bloginfo('template_url')?>/images/fcg-feature-demo.jpg" alt="Plugin Demo" />
            </div>    
        <?php endif;?>
	<?php endif; ?>
	
	<?php if (have_posts()) : ?>
		<?php
        $i = 0;
        while (have_posts()) {
            the_post(); 
            include(dirname(__FILE__).'/post.php');
            
			//Insert custom content between posts
			if ($html = get_option($shortname.'_custom_html_'.$i)) {
                echo "<div class='customhtml'>$html</div>";
            }
        $i++;
        }
		?>
	<?php endif; ?>
    
    <div class="navigation">
        <p class="alignleft"><?php previous_posts_link('Latest posts'); ?></p>
        <p class="alignright"><?php next_posts_link('Older posts'); ?></p>
    </div>

<?php get_footer(); ?>