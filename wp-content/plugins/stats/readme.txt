=== WordPress.com Stats ===
Contributors: automattic, skeltoac, mdawaffe, tmoorewp
Tags: stats, statistics, views
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: 1.8.6

WordPress.com stats has merged into Jetpack. Please use Jetpack instead of this plugin.

== Description ==

WordPress.com stats has merged into Jetpack and all future upgrades to WordPress.com Stats will only be available in <a href="http://jetpack.me/">Jetpack</a>. Jetpack connects your blog to the WordPress.com cloud, <a href="http://jetpack.me/faq/">enabling awesome features</a>. <a href="http://downloads.wordpress.org/plugin/jetpack.latest-stable.zip">Get Jetpack now!</a>



== Screenshots ==

1. Your stats are displayed in a frame on your own blog's dashboard. There are graphs and several sections of charts below. You will need to be logged in at WordPress.com to see the stats. If you see a login box here, use your WordPress.com login.

2. Each post has its own graph.

3. You can add other WordPress.com users to the list of people allowed to see your stats.

== Installation ==

Please install <a href="http://jetpack.me">Jetpack</a>.

== Frequently Asked Questions ==

= Can I keep using existing stat systems like Mint, Google Analytics, and Statcounter? =

Of course, nothing we do conflicts with any of those systems. We're just (hopefully) faster.

= How long before I start seeing stats? =

It may take as long as 20 minutes the first time you use it. After that they should update every 3 minutes or so.

= Does it count my own hits? =

There is an option to disable counting the hits of logged-in users.

= What if the stats don't start showing up? Do I need anything special in my theme? =

Yes, your theme must have a call to `<?php wp_footer(); ?>` at the very bottom right before the `</body>` tag. (Typically in your theme's footer.php, but some themes put this in more than one place.)

= Can I hide the smiley? =

Sure, just use `display:none`. Try this code in your stylesheet (style.css in your theme):

`img#wpstats{display:none}`

= Is it compatible with WP-Cache? =

The plugin collects stats via a javascript call, so as long as the JS call is on the page stats will be collected just fine, whether the page is cached or not.

= Can I use the same API key on multiple blogs? =

You're welcome to use the same API key on multiple blogs. If you view your stats directly on WordPress.com, you can easily switch between all of your blogs' stats reports.

= How do I add a Top Posts widget to my blog? =

We opened our database for developers to retrieve stats. The API is at `http://stats.wordpress.com/csv.php` and the plugin includes a handy function, `stats_get_csv()`, which you can use to get your most popular posts. Here is code you can add to your theme based on the work of <a href="http://www.binarymoon.co.uk/2010/03/ultimate-add-popular-posts-wordpress-blog-1-line-code/">Ben Gillbanks</a>:

`<?php if ( function_exists('stats_get_csv') && $top_posts = stats_get_csv('postviews', 'days=7&limit=8') ) : ?>
	<h3>Currently Hot</h3>
	<ol>
<?php foreach ( $top_posts as $p ) : ?>
		<li><a href="<?php echo $p['post_permalink']; ?>"><?php echo $p['post_title']; ?></a></li>
<?php endforeach; ?>
	</ol>
<?php endif; ?>`

== Changelog ==

= 1.8.5 =
* Fixed typo.

= 1.8.4 =
* Added notice to upgrade to Jetpack

= 1.8.2 =
* Fix always-on admin_bar option (reported by sillybean)
* Send more post data to the API (no content or passwords)
* Send more blog data to the API for generating reports
* Fix API key reset link
* Update readme to mention Quantcast and Jetpack

= 1.8.1 =
* Drop SSL from server-to-server requests for stats reports
* Prepare dashboard widget for upgrade to flot charts

= 1.8 =
* Fix Notice: Undefined index: HTTPS (props teetilldeath)
* Add chart to admin bar with 48-hour views sparkline
* Add color and ssl parameters to proxy calls
* Update FAQ

= 1.7.5 =
* Fix deprecated function call_user_method_array (props Galeforce99)
* Move data deletion to uninstall hook (props viper007bond)
* Change "Get your key here" link to apikey.wordpress.com

= 1.7.4 =
* Reinstate deactivation hook

= 1.7.3 =
* Add domain to gettext calls and load_plugin_textdomain (props nbachiyski)
* Add POT file and Text Domain
* Change some escaping functions (props nbachiyski)
* Remove delete_option from deactivation hook

= 1.7.2 =
* Remove footer test pending investigation of errors.

= 1.7.1 =
* Add real-time test to check footer immediately prior to displaying notice. Should help automatic upgraders.

= 1.7 =
* Remove support for hard-coded API key as $stats_wpcom_api_key
* Update admin screen after API key entry to add Recommended Action and clarify instructions
* Add option to disable wp.me shortlinks
* Add option to select roles with stats report visibility
* Add option to enable tracking logged-in users
* When activated for entire network, do not show admin notice about missing API keys
* When plugins.php disabled, move stats admin page to options-general.php
* Add check for footer code and display a helpful notice if not detected

= 1.6.3 =
* Add support for shortlink API in WordPress 3.0
* Add CSV usage example to readme
* Remove .htaccess
* Remove all-time stats from dashboard widget

= 1.6.2 =
* Fix infinite loop when shortlink function dec2sixtwo called with a negative number. Thanks to Andrew Mattie for identifying this bug.
* Fix unescaped Top Searches section in dashboard module. Thanks to Kobi for the report.

= 1.6.1 =
* Fix attachment titles in post stats list.
* Fix chart not appearing due to .htaccess directive installed by some users. Thanks to Sean at growingmoneyblog.com for access to his affected system.

= 1.6 =
* Add shortlink generator. Now wp.me shortlinks are available on the Edit Post screen from a button next to View Post.

= 1.5.4 =
* Work around core API change in plugins_url. Different code for 2.7. Fixes missing charts in 2.7.*. No changes for 2.8+.

= 1.5.3 =
* Restore backward compatibility for WordPress 2.7. Fixes "Call to undefined function plugin_dir_url()..."

= 1.5.2 =
* Fix dashboard chart missing due to omitted line of code.

= 1.5.1 =
* Include <a href="http://teethgrinder.co.uk/open-flash-chart/">Open Flash Chart</a> SWF. Faster and more reliable than proxying it. Should fix missing graph for many users.
* Move change log out of source code.
* Fixed an XMLRPC encoding issue that resulted in "malformed" error when entering API key. Thanks to Oscar Reixa for helping.

= 1.5 =
* Kill iframes.
* Use blog's role/cap system to allow local users to view reports. (No more switcher.)
* Thanks to Stefanos Kofopoulos for helping to debug encoding issues.

= 1.4 =
* Added gmt_offset setting to blog definition. (Stats in your time zone.)

= 1.3.8 =
* Fixed "Missing API Key" error appearing in place of more helpful errors. Hat tip: Walt Ritscher.

= 1.3.7 =
* If blog dashboard is https, stats iframe should be https.

= 1.3.6 =
* fopen v wp_remote_fopen CSV fix from A. Piccinelli

= 1.3.5 =
* Compatibility with WordPress 2.7

= 1.3.4 =
* Compatibility with WordPress 2.7

= 1.3.3 =
* wpStats.update_postinfo no longer triggered by revision saves (post_type test)
