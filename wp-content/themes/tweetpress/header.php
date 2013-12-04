<?php
/**
 * @package WordPress
 * @subpackage TweetPress
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<?php if(!is_404()) : ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/jquery.scrollTo-min.js"></script>
<?php
	$paged = intval(get_query_var('paged'));
	if($paged==undefined||$paged==0)$paged=1;
	$maxpages = $wp_query->max_num_pages;
	$uri = get_bloginfo('template_url') . '/tweetpress.js.php?paged=' . $paged . '&maxpages=' . $maxpages;
	if(isset($_POST['status'])) {
		$txt = $_POST['status'];
		if(post_twitter_status($txt)) {
			$uri .= '&status=' . urlencode($txt);
		}
	}
?>
<script type="text/javascript" src="<?php echo $uri; ?>"></script>
<?php endif; ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="container" class="subpage">


<div id="header" role="banner">
	<a id="logo" accesskey="1" title="<?php bloginfo('description'); ?>" alt="<?php bloginfo('description'); ?>" href="<?php echo get_option('home'); ?>/">
		<img src="<?php bloginfo('template_url'); ?>/images/logo-default.png" width="206" height="45" alt="<?php bloginfo('name'); ?>" />
	</a>
	<ul class="top-navigation round">
		<li><a href="<?php echo get_option('home'); ?>/" alt="Home" title="Home">Home</a></li>
		<?php if(!is_404()) : ?>
		<li><a href="http://twitter.com/<?php global $screen_name; echo $screen_name; ?>">Profile</a></li>
		<?php wp_register(); ?>
		<li><a href="http://feeds.feedburner.com/<?php global $fbacct; echo $fbacct; ?>">Subscribe</a></li>
		<li><?php wp_loginout(); ?></li>
		<?php endif; ?>
	</ul>
</div>
<div class="content-bubble-arrow"></div>

<?php if(!is_404()) : ?>
<table class="columns" cellspacing="0">
	<tr>
<?php endif; ?>