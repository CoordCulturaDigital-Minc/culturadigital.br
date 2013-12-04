<?php
/*
Template Name: Landing Page
*/

$body_css = '';
$body_css .= (get_option('tbf1_background_image_file')) ? 'background-image:url('.get_option('tbf1_background_image_file'). ');' : '';
$body_css .= (get_option('tbf1_background_color')) ? 'background-color:'.get_option('tbf1_background_color'). ';' : '';
$body_css .= (get_option('tbf1_background_repeat')) ? 'background-repeat:'.get_option('tbf1_background_repeat'). ';' : '';

$skin_folders = array('silver'=>'skin-silver', 'red'=>'skin-red', 'green'=>'skin-green');

foreach($skin_folders as $key=>$value) {
	if(get_option('tbf1_skin_color') == $key) {
		$skin_dir = $value;
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title>
	<?php echo ($title = wp_title('&#8211;', false, 'right')) ? $title : ''; ?><?php echo ($description = get_bloginfo('description')) ? $description : bloginfo('name'); ?>
</title>

<meta name="author" content="<?php bloginfo('name'); ?>" />
<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.ico" type="image/x-icon" />
<?php wp_enqueue_script('jquery'); ?>
<?php if (is_singular()) wp_enqueue_script('comment-reply'); ?>
<?php wp_head(); ?>

<link href="<?php bloginfo('template_url'); ?>/style.css" type="text/css" rel="stylesheet" />
<?php if(isset($skin_dir)):?>
<link href="<?php bloginfo('template_url'); ?>/images/<?php echo $skin_dir?>/style.css" type="text/css" rel="stylesheet" />
<?php endif;?>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/functions.js"></script>
</head>

<body <?php echo ($body_css) ? 'style="'.$body_css. '"' : ''?> class="landingpage">

<div id="bg" <?php echo (get_option('tbf1_header_image_file')) ? 'style="background-image:url('.get_option('tbf1_header_image_file'). ')"' : ''?>>
	<div id="shadow">
		<?php if(get_option('tbf1_landing_header') != 'hidden'): ?>
		<div id="header">
          <h1 id="logo">
			<?php if (get_option('tbf1_logo_header') == "yes" && get_option('tbf1_logo')) { ?>
                    <a href="<?php bloginfo('url'); ?>/"><img src="<?php echo get_option('tbf1_logo'); ?>" title="<?php bloginfo('name'); ?> - 
					<?php bloginfo('description'); ?>" alt="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>" /></a>
            <?php } else { //If no logo, show the blog title and tagline by default ?>
            	<a href="<?php bloginfo('url'); ?>" id="blogname" style="background:none;text-indent:0;width:auto"><span class="blod"><?php bloginfo('name'); ?></span><br /><?php bloginfo('description'); ?></a>
            <?php } ?>
          </h1>
        </div>
		<?php endif; ?>
		
		<div id="container">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php edit_post_link('Edit', '<p><span class="editpost">', '</span></p>'); ?>
			<?php the_content(''); ?>
			<?php endwhile; endif; ?>
		</div>
		
		<?php if(get_option('tbf1_landing_footer') != 'hidden'): ?>
			<div id="footer" <?php echo (get_option('tbf1_footer_image_file')) ? 'style="background:url('.get_option('tbf1_footer_image_file'). ') no-repeat"' : ''?>>
			<div class="footer-content">
				<div class="footer-widget">
					<ul class="footerlinks">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Left") ) : ?>
						<li><strong>Footer Left Content</strong><br />To replace this texts, go to "Widgets" page and start adding your own widgets to "Footer Left" section.</li>
						<?php endif; ?>	
					</ul>
				</div>
				<div class="footer-widget">
					<ul class="footerlinks">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Middle") ) : ?>
						<li><strong>Footer Middle Content</strong><br />To replace this texts, go to "Widgets" page and start adding your own widgets "Footer Middle" section.</li>
						<?php endif; ?>	
					</ul>
				</div>
				<div class="footer-widget">
					<ul class="footerlinks">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Right") ) : ?>
						<li><strong>Footer Right Content</strong><br />To replace this texts, go to "Widgets" page and start adding your own widgets "Footer Right" section.</li>
						<?php endif; ?>	
					</ul>
				</div>
			  <div class="recover"></div>
			  </div>
			  
                <span id="copyright"><span class="alignleft"><?php _e('Copyright &copy; ')?>
                <script type="text/javascript">
				/* <![CDATA[ */
				var startCopyrightYear = <?php echo (get_option('tbf1_copy_year')) ? get_option('tbf1_copy_year') : "''" ?>;
				if(!startCopyrightYear) {
					var d=new Date();
					startCopyrightYear = d.getFullYear();
				}
				printCopyrightYears(startCopyrightYear)
				/* ]]> */
				</script>
                <?php echo bloginfo('site_name')?></span><span id="footer-tag"> | &nbsp; <a href="http://www.topblogformula.com/wordpress-business-themes/intrepidity" target="_blank">intrepidity</a> Theme <?php _e('by')?> <a href="http://www.topblogformula.com/" target="_blank">Top Blog Formula</a> on <a href="http://www.wordpress.org" target="_blank">WordPress</a></span> | &nbsp; 
                <?php if(is_user_logged_in()):?>
                    <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php echo _e('Log Out') ?>"><?php echo _e('Log Out'); ?></a>
                <?php else:?>
                    <a href="<?php echo bloginfo('url')?>/wp-login.php"><?php _e('Log In'); ?></a>
                <?php endif;?>
              </span>

			</div><!--/footer-->
		<?php else: ?>
			<div id="footer-bottom"></div>
		<?php endif; ?>
	  
	</div><!--/shadow-->
</div><!--/bg-->
				
<?php wp_footer(); ?>
</body>
</html>