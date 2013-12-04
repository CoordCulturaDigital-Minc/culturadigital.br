<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="description" content="<?php bloginfo('description'); ?>" />
	<title><?php bloginfo('name'); ?> &raquo; <?php echo $this->g_opt['mamo_pagetitle']; ?></title>
	
	<style type="text/css">
		<!--
		* { margin: 0; 	padding: 0; }
		body { font-family: Georgia, Arial, Helvetica, Sans Serif; font-size: 65.5%; }
		a { color: #08658F; }
		a:hover { color: #0092BF; }
		#header { color: #333; padding: 1.5em; text-align: center; font-size: 1.2em; border-bottom: 1px solid #08658F; }
		#content { font-size: 150%; width:80%; margin:0 auto; padding: 5% 0; text-align: center; }
		#content p { font-size: 1em; padding: .8em 0; }
		h1, h2 { color: #08658F; }
		h1 { font-size: 300%; padding: .5em 0; }
		#menu { position: absolute; font-family: Arial, Helvetica, Sans Serif; bottom: 2em; width: 100%; border-top: 1px solid #08658F; }
		#menu #pluginauthor { padding-left: .3em; }
		#menu #admin { float: right; padding-right: .3em; }
		-->
	</style>
	
</head>

<body>

	<div id="header">
		<h2><a title="<?php bloginfo('name'); ?>" href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a></h2>
	</div>
	
	<div id="content">
		<?php 
			$mamo_msg = stripslashes($this->g_opt['mamo_pagemsg']);
			$mamo_msg = str_replace('[blogurl]', get_settings('home'), $mamo_msg);
			$mamo_msg = str_replace('[blogtitle]', get_bloginfo('name'), $mamo_msg);
			$mamo_msg = str_replace('[backtime]', $this->g_opt['mamo_backtime'], $mamo_msg);
			echo $mamo_msg;
		?>		
		
	</div>

	<div id="menu">
		<p id="admin"><?php		
			global $user_ID, $wp_version;
			get_currentuserinfo();
			// Get URLs for login/logout
			// wp_logout_url() does not work here for some unknown reason...
			if ( version_compare($wp_version, '2.6', '>=' ) ) {		// site_url() and admin_url() are there since WP 2.6.0
				$loginurl = site_url('wp-login.php', 'login');
				$logouturl = wp_nonce_url( site_url('wp-login.php?action=logout', 'login'), 'log-out' );
				$adminurl = admin_url(); // also since 2.6.0
			} else {
				$loginurl = trailingslashit(get_settings('siteurl')).'wp-login.php';
				$logouturl = wp_nonce_url( trailingslashit(get_settings('siteurl')).'wp-login.php?action=logout', 'log-out' );
				$adminurl = trailingslashit(get_settings('siteurl')) . 'wp-admin/';
			}

			if ($user_ID) {
				if ($status == 'noaccesstobackend' ) {
					_e('(Access to administration denied by administrator)',$this->g_info['ShortName']);
				} else {
					echo '<a rel="nofollow" href="' . $adminurl . '">Administration</a>';
				}
				echo ' | <a rel="nofollow" href="'. $logouturl . '">Log Out</a>';	 

			} else {
				?><a rel="nofollow" href="<?php echo $loginurl; ?>">Log In</a><?php	
			}
			?></p>
		<p id="pluginauthor">Maintenance Mode plugin provided by <a title="Software Guide" href="http://sw-guide.de/">Software Guide</a>.</p>



	</div>

</body>
</html>