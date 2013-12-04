<?php

/**
 * In this file you'll want to add filters to the template tag output of your component.
 * You can use any of the built in WordPress filters, and you can even create your
 * own filter functions in this file.
 */

 /**
  * Some WP filters you may want to use:
  *  - wp_filter_kses() VERY IMPORTANT see below.
  *  - wptexturize()
  *  - convert_smilies()
  *  - convert_chars()
  *  - wpautop()
  *  - stripslashes_deep()
  *  - make_clickable()
  */

/**
 * --- NOTE ----
 * It's very very important that you use the wp_filter_kses() function to filter all
 * input AND output in your plugin. This will stop users adding malicious scripts and other
 * bad things onto any page.
 */

/**
 * In all your template tags that output data, you should have an apply_filters() call, you can
 * then use those filters to automatically add the wp_filter_kses() call.
 * The third parameter "1" adds the highest priority to the filter call.
 */
 
 add_filter( 'bp_wiki_get_item_name', 'wp_filter_kses', 1 );

/**
 * In your save() method in 'bp-wiki-classes.php' you will have 'before save' filters on
 * values. You should use these filters to attach the wp_filter_kses() function to them.
 */

 add_filter( 'wiki_data_fieldname1_before_save', 'wp_filter_kses', 1 );
 add_filter( 'wiki_data_fieldname2_before_save', 'wp_filter_kses', 1 );

/**
 * Filters for the template files.  These hook into bp_wiki_load_template_file to retrieve them files from
 * the current theme dir if the files are present, or fall back to the plugin dir theme files if not.
 */

// Directory access required so using template_file function
add_filter( 'bp_wiki_locate_edit_group_page', 'bp_wiki_load_template_file' );
add_filter( 'bp_wiki_locate_group_wiki_admin', 'bp_wiki_load_template_file' );
add_filter( 'bp_wiki_locate_group_wiki_create', 'bp_wiki_load_template_file' );
add_filter( 'bp_wiki_locate_view_group_index', 'bp_wiki_load_template_file' );
add_filter( 'bp_wiki_locate_view_group_page', 'bp_wiki_load_template_file' );
add_filter( 'bp_wiki_locate_view_group_revision', 'bp_wiki_load_template_file' );
add_filter( 'bp_wiki_locate_view_group_discussion', 'bp_wiki_load_template_file' );
add_filter( 'bp_wiki_locate_view_site_directory', 'bp_wiki_load_template_file' );
add_filter( 'bp_wiki_locate_view_site_page', 'bp_wiki_load_template_file' );

// URL access required so using template_url function
add_filter( 'bp_wiki_locate_group_css', 'bp_wiki_load_template_url' );
add_filter( 'bp_wiki_locate_group_wiki_title_image', 'bp_wiki_load_template_url' );
add_filter( 'bp_wiki_locate_group_wiki_page_image', 'bp_wiki_load_template_url' );
add_filter( 'bp_wiki_locate_group_wiki_revisions_image', 'bp_wiki_load_template_url' );
add_filter( 'bp_wiki_locate_group_wiki_comments_image', 'bp_wiki_load_template_url' );

?>