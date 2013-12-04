=== DB Cache ===
Contributors: posterd
Tags: performance,caching,wp-cache,db-cache
Tested up to: 2.7
Stable tag: 0.6
Requires at least: 2.5

The fastest cache engine for WordPress, that produces cache of database queries with easy configuration.

== Description ==
This plugin caches every database query with given lifetime. It is much faster than other html caching plugins and uses less disk space for caching.

I think you've heard of [WP-Cache](http://wordpress.org/extend/plugins/wp-cache/) or [WP Super Cache](http://wordpress.org/extend/plugins/wp-super-cache/), they are both top plugins for WordPress, which make your site faster and responsive. Forget about them - with DB Cache your site will work much faster and will use less disk space for cached files. Your visitors will always get actual information in sidebars and server CPU loads will be as low as posible.

See the [DB Cache homepage](http://wordpress.net.ua/db-cache/) for further information.

== Installation ==
1. You should upload the db-cache folder to your wp-content/plugins
2. Go to your Plugins page and activate "DB Cache"
3. Now go to Settings->DB Cache and enable caching of DB queries. The plugin will create wp-content/tmp folder and db.php file for serving cached queries in wp-content folder.

That's all! Enjoy the speed of loading pages.

== Frequently Asked Questions ==

= How do I know my blog is being cached? =

Check your cache directory wp-content/tmp/ for cache files. Check the load statistics in footer.
Also you can set DBC_DEBUG to true in db-cache.php file to display as hidden comments on your html page, what queries were loaded from cache and what from mysql.

= What does this plugin do? =

This plugin decreases count of queries to DB, which means that CPU load of your web-server decreases and your blog can serve much more visitors in one moment.

= What is page generation time? =

It is time from request to server (start of generation) and the generated page sent (end of generation). This time depends on server parameters: CPU speed, RAM size and the server load (how much requests it operates at the moment, popularity of sites hosted on the server) and of course it depends on how much program code it needs to operate for page generation.

Let set the fourth parameter as constant (we can't change the program code). So we have only 3: CPU, RAM and popularity.

If you have a powerful server (costs more) it means that will be as low as possible and it can serve for example 100 visitors in one moment without slowing down. And another server (low cost) with less CPU speed and RAM size, which can operate for example 10 visitors in one moment. So if the popularity of your site grows it is needed more time to generate the page. Thats why you need to use any caching plugins to decrease the generation time.

= How can I ensure of reducing server usage? =

From 0.3 version plugin can show usage statistics with your custom template in your footer.

Checking count of queries, ensure that other cache plugins are disabled, because you can see cached number.

View the source of your site page, there maybe some code like this at the foot:

	<!-- 00 queries. 00 seconds. -->

If not, please put these codes in your footer template:

	<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->

After using the DB Cache, I think you'll find the number of queries reducing a lot.

= Why is DB Cache better than WP Super Cache? =

This plugin is based on a fundamentally different principle of caching queries to database instead of full pages, which optimizes WordPress from the very beginning and uses less disk space for cache files because it saves only useful information.
It saves information separately and also caches hidden requests to database.

Analyzing server load graphs of my sites I can say that the peaks of server load are caused of search engines bots indexing your site (they load much pages practically in one moment). I've tried WP Super Cache to decrease the server loads but it was no help from it. Simply saying WP Super Cache saves any loaded page and much of these pages are opened only once by bots. My plugin roughly saves parts of web-page (configuration, widgets, comments, content) separately, which means that once configuration is cached it will be loaded on every page.

Here is the Google translation of [my article](http://translate.google.com/translate?prev=&hl=uk&u=http%3A%2F%2Fwordpress.net.ua%2Fmaster%2Foptimizaciya-wordpress.html&sl=uk&tl=en) on it

= Troubleshooting =

Make sure wp-content is writeable by the web server. If not you'll need to [chmod](http://codex.wordpress.org/Changing_File_Permissions) wp-content folder for writing.

= How do I uninstall DB Cache? =

1. Disable it at Settings->DB Cache page. The plugin will automatically delete all cache files. If something went wrong - delete /wp-content/db.php, /wp-content/db-config.php and /wp-content/tmp folder. While db.php file exists WordPress will use our optimized DB class instead of own.
2. Deactivate it at plugins page.

== Other Notes ==

= Updates =
Updates to the plugin will be posted here and the [Ukrainian WordPress](http://wordpress.net.ua/db-cache/) will always link to the newest version.

= Thanks =
I would like to thank [Lviv](http://v.lviv.ua/) for giving me the idea for this and [RKG](http://design.lviv.ua) for support.

== Changelog ==

= Version 0.6 =
Added it_IT localization. Thanks to Iacopo Benesperi, http://iacchi.org ;
Added tr_TR localization. Thanks to offchu, offchu.kptngl.net ;
Added subfolders for caching;
Improved caching queries filters;
Added display of debug info;
Added filters for caching;

= Version 0.5 =
Added clearing cache on new post or comment;
Removed tables checking at options page - all tables will be cached;
Refactored code;
Changed cache load function;

= Version 0.4 =
Checked autocleaning;
Corrected uninstallation of plugin;
Disabled caching for cron tasks;

= Version 0.3 =
Added resourses usage statistics in wp_footer;
Checked some problems;

= Version 0.2 =
Added nl_NL localization. Thanks to http://golabs.nl/2009/10335/ ;
Disabled caching for register and login pages;
Changed options saving path to wp-content;
Corrected removing tmp folder; 

= Version 0.1 =
Start