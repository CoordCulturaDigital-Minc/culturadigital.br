=== Highlight sticky posts ===
Tags: sticky, posts, featured posts, highlight posts
Requires at least: 2.7.0
Tested up to: 3.0

This plugin creates a new admin page called `Sticky posts` on posts menu that allow`s you to change sticky posts order.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `/hl-stick-posts/` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php query_posts( array( 'posts__in' =>
get_option('sticky_posts'), 'orderby' => 'menu_order', 'order' =>
'ASC' ) ); ?>` where you want to call sticky posts before posts loop in your template
4. Call posts loop in your template. `<?php if( have_posts() ) {
while( have_posts() ) { the_post() ... } } ?>`

== Screenshots ==

1. `Sticky posts` admin page on posts menu `/screenshot.png`
