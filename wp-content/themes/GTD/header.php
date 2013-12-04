<?php
//print_r(get_option('members_only_options'));
wp_get_current_user();
$userinfo = getLoginUserInfo();
if(get_option('feed_access')=='require_login' && !getLoginUserInfo())
{
	wp_redirect(get_option('siteurl')."/wp-login.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?3" type="text/css" media="screen" />
    <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php wp_head(); ?>
</head>
<body<?php if(is_single()) echo ' class="single"'; ?>>

<div id="header">
	<div class="sleeve">
    	<div class="header_left">
		<h1><a href="<?php bloginfo( 'url' ); ?>/"><?php bloginfo( 'name' ); ?></a></h1>
			<?php if(get_bloginfo('description')) : ?><small><?php bloginfo( 'description' ); ?></small><?php endif; ?>
            
           <?php
           if($userinfo['ID'])
		   {
		   ?> 
            <p class="login ">Hi <strong> <a href="<?php echo get_option('siteurl');?>/wp-admin/profile.php"><?php echo $userinfo['display_name']; ?></a></strong>, | <a href="<?php echo get_option('siteurl');?>/wp-admin/post-new.php">Add Post</a> | <a href="<?php echo get_option('siteurl');?>/wp-login.php?action=logout&_wpnonce=">Logout</a> </p>
      
      <?php
      }else
	  {
	  	?>
        <p class="login ">Hi <strong>Guest,</strong> <a href="<?php echo get_option('siteurl');?>/wp-login.php">Login</a></p>
        <?php
	  }
	  ?>    </div>
            
            
            
            <ul class="nav">
            	 <?php wp_list_pages('exclude=;&depth=1&sort_column=menu_order&title_li=' . ('') . '' ); ?>
            </ul>
            
            
	</div>
</div>


<div id="wrapper">
	<?php get_sidebar( );?>