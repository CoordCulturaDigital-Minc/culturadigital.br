<?php
wp_get_current_user();
get_currentuserinfo();
if(get_option('feed_access')=='require_login')
{
	if(!getLoginUserInfo() && (strstr($_SERVER['REQUEST_URI'],'feed') || strstr($_SERVER['REQUEST_URI'],'rss2')))
	{
		echo "<h2><strong>You need to use a Feed Key to access feeds on this site. Please login to obtain yours.</strong></h2>";
		exit;
	}
	
}
function getLoginUserInfo()
{
	$logininfoarr = explode('|',$_COOKIE[LOGGED_IN_COOKIE]);
	if($logininfoarr)
	{
		global $wpdb;
		$userInfoArray = array();
		$usersql = "select * from $wpdb->users where user_login = '".$logininfoarr[0]."'";
		$userinfo = $wpdb->get_results($usersql);
		foreach($userinfo as $userinfoObj)
		{
			$userInfoArray['ID'] = 	$userinfoObj->ID;
			$userInfoArray['display_name'] = 	$userinfoObj->display_name;
			$userInfoArray['user_nicename'] = 	$userinfoObj->user_login;
			$userInfoArray['user_email'] = 	$userinfoObj->user_email;
			$userInfoArray['user_id'] = 	$logininfoarr[0];
		}
		return $userInfoArray;
	}else
	{
		return false;
	}
}
$content_width = '691';



add_filter( 'the_content', 'make_clickable' );



add_action( 'wp_ajax_prologue_ajax_tag_search', 'prologue_ajax_tag_search' ); //Tag suggestion

add_action( 'wp_ajax_prologue_load_post', 'prologue_load_post' ); // Load posts for inline editing

add_action( 'wp_ajax_prologue_load_comment', 'prologue_load_comment' ); // Load comments for inline editing

add_action( 'wp_ajax_prologue_inline_save', 'prologue_inline_save' ); // Save post  after inline editing

add_action( 'wp_ajax_prologue_inline_comment_save', 'prologue_inline_comment_save' ); // Save comment   after inline editing

add_action( 'wp_ajax_prologue_latest_posts', 'prologue_latest_posts' ); // Load new posts 

add_action( 'wp_ajax_prologue_latest_comments', 'prologue_latest_comments' ); //check for new comments and loads comments into widget

add_action( 'wp_ajax_prologue_new_post', 'prologue_new_post' ); //Ajax posting

add_action( 'wp_ajax_prologue_new_comment', 'prologue_new_comment' ); //Ajax Commenting

add_action( 'wp_head', 'prologue_widget_recent_comments_avatar_style'); //Load styles for recent comments avatar widget



if( !class_exists('Services_JSON') ) include (TEMPLATEPATH . '/inc/JSON.php');



if (!is_admin()) add_action( 'wp_print_scripts', 'prologue_javascript' );



function prologue_init() {

    load_theme_textdomain( 'p2', get_template_directory() . '/languages' );

}

add_action( 'init', 'prologue_init' );



function prologue_javascript() {

    $prologue_tagsuggest = true;

    $prologue_inlineedit = true;

    

    wp_enqueue_script( 'jquery-color' );

	wp_enqueue_script( 'comment-reply' );



	if ( is_front_page() && $prologue_tagsuggest && is_user_logged_in() )

		wp_enqueue_script( 'suggest' );

		

	wp_enqueue_script( 'p2js', get_bloginfo('template_directory' ).'/inc/p2.js', array( 'jquery' ), '200903301' );

	wp_localize_script( 'p2js', 'p2txt', array(

	    'tagit' => __('Tag it', 'p2'),

	    'goto_homepage' => __('Go to homepage', 'p2'),

	    // the number is calculated in the javascript in a complex way, so we can't use ngettext

	    'n_new_updates' => __('%d new update(s)', 'p2'),

	    'n_new_comments' => __('%d new comment(s)', 'p2'),

	    'jump_to_top' => __('Jump to top', 'p2'),

	    'not_posted_error' => __('An error has occured, your post was not posted', 'p2'),

	    'update_posted' => __('You update has been posted', 'p2'),

	    'loading' => __('Loading...', 'p2'),

	    'cancel' => __('Cancel', 'p2'),

	    'save' => __('Save', 'p2'),

	    'hide_threads' => __('Hide threads', 'p2'),

	    'show_threads' => __('Show threads', 'p2'),

		'unsaved_changes' => __('Your comments or posts will be lost if you continue.', 'p2'),

	));

	

	if ( ( $prologue_inlineedit ) && is_user_logged_in() )

		wp_enqueue_script( 'jeditable', get_bloginfo('template_directory').'/inc/jquery.jeditable.js', array( 'jquery' )  );



	wp_enqueue_script( 'scrollit', get_bloginfo('template_directory').'/inc/jquery.scrollTo-min.js', array( 'jquery' )  );

}



function prologue_pageoptions_init() {

	global $page_options;

	get_currentuserinfo();

	$page_options['nonce']= wp_create_nonce( 'ajaxnonce' );

	$page_options['prologue_updates'] = 1;

	$page_options['prologue_comments_updates'] = 1;

	$page_options['prologue_tagsuggest'] = 1;

	$page_options['prologue_inlineedit'] = 1;

	$page_options['prologue_comments_inlineedit'] = 1;

	$page_options['is_single'] = (int)is_single();

	$page_options['is_page'] = (int)is_page();

	$page_options['is_front_page'] = (int)is_front_page();

	$page_options['is_first_front_page'] = (int)(is_front_page() && !is_paged() );

	$page_options['is_user_logged_in'] = (int)is_user_logged_in();

}

add_action('wp_head', 'prologue_pageoptions_init');



function prologue_pageoptions_js() {

	global $page_options;

?><script type='text/javascript'>

// <![CDATA[

//Prologue Configuration

var ajaxUrl = "<?php echo js_escape( get_bloginfo( 'wpurl' ) . '/wp-admin/admin-ajax.php' ); ?>";

var updateRate = "30000";

var nonce = "<?php echo js_escape( $page_options['nonce'] ); ?>";

var templateDir  = "<?php js_escape( bloginfo('template_directory') ); ?>";

var isFirstFrontPage = <?php echo $page_options['is_first_front_page'] ?>;

var isFrontPage = <?php echo $page_options['is_front_page'] ?>;

var isSingle = <?php echo $page_options['is_single'] ?>;

var isPage = <?php echo $page_options['is_page'] ?>;

var isUserLoggedIn = <?php echo $page_options['is_user_logged_in'] ?>;

var prologueTagsuggest = <?php echo $page_options['prologue_tagsuggest'] ?>;

var prologuePostsUpdates = <?php echo $page_options['prologue_updates'] ?>;

var prologueCommentsUpdates = <?php echo $page_options['prologue_comments_updates']; ?>;

var getPostsUpdate = 0;

var getCommentsUpdate = 0;

var inlineEditPosts =  <?php echo $page_options['prologue_inlineedit'] ?>;

var inlineEditComments =  <?php echo $page_options['prologue_comments_inlineedit'] ?>;

var wpUrl = "<?php echo js_escape( get_bloginfo( 'wpurl' ) ); ?>";

var rssUrl = "<?php js_escape( get_bloginfo( 'rss_url' ) ); ?>";

var pageLoadTime = "<?php echo gmdate( 'Y-m-d H:i:s' ); ?>";

var latestPermalink = "<?php echo js_escape( latest_post_permalink() ); ?>";

var original_title = document.title;

var commentsOnPost = new Array;

var postsOnPage = new Array;

var postsOnPageQS = '';

var currPost = -1;

var currComment = -1;

var commentLoop = false;

var lcwidget = false;

var hidecomments = false;

var commentsLists = '';

var newUnseenUpdates = 0;

 // ]]>

</script> <?php

}

add_action('wp_head', 'prologue_pageoptions_js');





add_action('admin_menu', 'prologue_plugin_menu');



function prologue_plugin_menu() {

  if(get_option( 'feed_access') == '')
  {
  		update_option( 'feed_access', 'open_feed' );
		update_option( 'post_attachment', '1' );
		update_option( 'post_notification', '1' );
  }
  add_theme_page('GTD Options', 'GTD Options', 8, __FILE__, 'prologue_options_page');

}





function prologue_options_page() {



    // variables for the field and option names 

    $opt_name = 'prologue_show_titles';

    $hidden_field_name = 'prologue_submit_hidden';

    $data_field_name = 'prologue_titles';



    // Read in existing option value from database

    $opt_val = get_option( $opt_name );
	$feed_access = get_option( 'feed_access' );
	$redirect_afterlogin = get_option( 'redirect_afterlogin' );
	$post_attachment = get_option( 'post_attachment' );
	$post_notification = get_option( 'post_notification' );
	$site_email = get_option( 'site_email' );
	$site_email_name = get_option( 'site_email_name' );


    // See if the user has posted us some information

    // If they did, this hidden field will be set to 'Y'

   if($_POST)
   {
     
   $feed_access = $_POST['feed_access'];
   if($feed_access == 1)
   {
   	$feed_access = "require_login";
   }else
   {
   	$feed_access = "open_feed";
   }
   $post_attachment =  $_POST['post_attachment'];
   if($post_attachment == 1)
   {
   	$post_attachment = "1";
   }else
   {
   	$post_attachment = "0";
   }
   $post_notification =  $_POST['post_notification'];
   if($post_notification == 1)
   {
   	$post_notification = "1";
   }else
   {
   	$post_notification = "0";
   }
   $site_email =  $_POST['site_email'];
   $site_email_name =  $_POST['site_email_name'];
   
   
   
   	$feedarray = array(
			"feed_access" => $feed_access,
			"post_attachment" => $post_attachment,
			"post_notification" => $post_notification,	
			"site_email" => $_POST['site_email'],	
			"site_email_name" => $_POST['site_email_name'],					
			
	);
   		foreach($feedarray as $key=>$val)
		{
	 		update_option( $key, $val );
		}
   }
    if( $_POST[ $hidden_field_name ] == 'Y' ) {

        // Read their posted value

		$opt_val = 0;

		if($_POST[ $data_field_name ] == 1) {

			$opt_val = 1;

		}



        // Save the posted value in the database

        update_option( $opt_name, $opt_val );



        // Put an options updated message on the screen

?>

<div class="updated"><p><strong><?php _e('Options saved.', 'p2' ); ?></strong></p></div>

<?php



    }



    // Now display the options editing screen



    echo '<div class="wrap">';



    // header



    echo "<h2>" . __( 'GTD Options', 'p2' ) . "</h2>";



    // options form

    

    ?>



<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y" />



<p>

	<input id="prologue-display-titles" type="checkbox" name="<?php echo $data_field_name; ?>" <?php if($opt_val == 1) echo 'checked="checked"'; ?> value="1" />

	<label for="prologue-display-titles"><?php _e("Display titles", 'p2' ); ?></label> 

</p>
<p>
<input id="prologue-display-titles1" type="checkbox" name="feed_access" value="1" <?php if($feed_access == 'require_login'){?> checked="checked"<?php }?> /> 
<label for="prologue-display-titles1"><?php _e("Private Blog", 'p2' ); ?> </label>
<?php /*?><select name="feed_access">
<option value="open_feed" <?php if($feed_access == 'open_feed'){?> selected="selected"<?php }?>>Open Feeds</option>
<option value="require_login" <?php if($feed_access == 'require_login'){?> selected="selected"<?php }?>>Require User Login</option>
</select><?php */?>
</p>
<p>
<input id="prologue-display-titles2" type="checkbox" name="post_attachment" value="1" <?php if($post_attachment == '1'){?> checked="checked"<?php }?> />
<label for="prologue-display-titles2"><?php _e("Enable File Attachment", 'p2' ); ?></label> 
<?php /*?><select name="post_attachment">
<option value="1" <?php if($post_attachment == '1'){?> selected="selected"<?php }?>>Yes</option>
<option value="0" <?php if($post_attachment == '0'){?> selected="selected"<?php }?>>No</option>
</select><?php */?>
</p>
<p>
<input id="prologue-display-titles3" type="checkbox" name="post_notification" value="1" <?php if($post_notification == '1'){?> checked="checked"<?php }?> /> 
<label for="prologue-display-titles3"><?php _e("Enable User Notifications", 'p2' ); ?></label> 
<?php /*?><input type="radio" name="post_notification" value="1" <?php if($post_notification == '1'){?> checked="checked"<?php }?> /> Yes
&nbsp;<input type="radio" name="post_notification" value="0" <?php if($post_notification == '0'){?> checked="checked"<?php }?> /> No<?php */?>

<p>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="prologue-display-titles4"><?php _e("From Email", 'p2' ); ?></label> 
    <input type="text" name="site_email" value="<?php echo $site_email;?>" />

</p>
<p>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="prologue-display-titles5"><?php _e("From Email Name", 'p2' ); ?></label> 
    <input type="text" name="site_email_name" value="<?php echo $site_email_name;?>" />

</p>

<?php /*?><select name="post_notification">
<option value="1" <?php if($post_notification == '1'){?> selected="selected"<?php }?>>Yes</option>
<option value="0" <?php if($post_notification == '0'){?> selected="selected"<?php }?>>No</option>
</select><?php */?>
</p>

<p class="submit">

<input type="submit" name="Submit" value="<?php _e('Update Options', 'p2' ) ?>" />

</p>



</form>

</div>



<?php

 

}





function prologue_recent_projects_widget( $args ) {

	extract( $args );

	$options = get_option( 'prologue_recent_projects' );



	$title = empty( $options['title'] ) ? __( 'Recent Tags' , 'p2') : $options['title'];

	$num_to_show = empty( $options['num_to_show'] ) ? 35 : $options['num_to_show'];



	$num_to_show = (int) $num_to_show;



	$before = $before_widget;

	$before .= $before_title . wp_specialchars( $title ) . $after_title;



	$after = $after_widget;



	echo prologue_recent_projects( $num_to_show, $before, $after );

}



function prologue_recent_projects( $num_to_show = 35, $before = '', $after = '' ) {

	$cache = wp_cache_get( 'prologue_theme_tag_list', '' );

	if( !empty( $cache[$num_to_show] ) ) {

		$recent_tags = $cache[$num_to_show];

	} else {

		$all_tags = (array) get_tags( array( 'get' => 'all' ) );



		$recent_tags = array();

		

		foreach( $all_tags as $tag ) {

			if( $tag->count < 1 )

				continue;



			$tag_posts = get_objects_in_term( $tag->term_id, 'post_tag' );

			$recent_post_id = max( $tag_posts );

			$recent_tags[$tag->term_id] = $recent_post_id;

		}



		arsort( $recent_tags );



		$num_tags = count( $recent_tags );

		if( $num_tags > $num_to_show ) {

			$reduce_by = (int) $num_tags - $num_to_show;



			for( $i = 0; $i < $reduce_by; $i++ ) {

				array_pop( $recent_tags );

			}

		}



		wp_cache_set( 'prologue_theme_tag_list', array( $num_to_show => $recent_tags ) );

	}



	echo $before;

	echo "<ul>\n";



	foreach( $recent_tags as $term_id => $post_id ) {

		$tag = get_term( $term_id, 'post_tag' );

		$tag_link = get_tag_link( $tag->term_id );

?>



<li>

<a class="rss" href="<?php echo get_tag_feed_link( $tag->term_id ); ?>">RSS</a>&nbsp;<a href="<?php echo $tag_link; ?>"><?php echo wp_specialchars( $tag->name ); ?></a>&nbsp;(&nbsp;<?php echo number_format_i18n( $tag->count ); ?>&nbsp;)

</li>



<?php

    } // foreach $recent_tags

?>



	</ul>



<p><a class="allrss" href="<?php bloginfo( 'rss2_url' ); ?>"><?php _e('All Updates RSS', 'p2'); ?></a></p>



<?php

	echo $after;

}



function prologue_flush_tag_cache() {

	wp_cache_delete( 'prologue_theme_tag_list' );

}

add_action( 'save_post', 'prologue_flush_tag_cache' );



function prologue_recent_projects_control() {

	$options = $newoptions = get_option( 'prologue_recent_projects' );



	if( $_POST['prologue_submit'] ) {

		$newoptions['title'] = strip_tags( stripslashes( $_POST['prologue_title'] ) );

		$newoptions['num_to_show'] = (int) strip_tags( stripslashes( $_POST['prologue_num_to_show'] ) );

	}



	if( $options != $newoptions ) {

		$options = $newoptions;

		update_option( 'prologue_recent_projects', $options );

	}



	$title = $options['title'];

	$num_to_show = (int) $options['num_to_show'];

?>



<input type="hidden" name="prologue_submit" id="prologue_submit" value="1" />



<p><label for="prologue_title"><?php _e('Title:', 'p2') ?> 

<input type="text" class="widefat" id="prologue_title" name="prologue_title" value="<?php echo attribute_escape($title); ?>" />

</label></p>



<p><label for="prologue_num_to_show"><?php _e('Num of tags to show:', 'p2') ?> 

<input type="text" class="widefat" id="prologue_num_to_show" name="prologue_num_to_show" value="<?php echo $num_to_show ?>" />

</label></p>



<?php

}

wp_register_sidebar_widget( 'prologue_recent_projects_widget', __( 'Recent Tags' , 'p2'), 'prologue_recent_projects_widget' );

wp_register_widget_control( 'prologue_recent_projects_widget', __( 'Recent Tags' , 'p2'), 'prologue_recent_projects_control' );



if( function_exists('register_sidebar') )

	register_sidebar();



function prologue_get_avatar( $user_id, $email, $size ) {

	if ( $user_id )

		return get_avatar( $user_id, $size );

	else

		return get_avatar( $email, $size );

}



function prologue_comment( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment;

?>

<li <?php comment_class(); ?> id="comment-<?php comment_ID( ); ?>">

	<?php echo prologue_get_avatar( $comment->user_id, $comment->comment_author_email, 32 ); ?>

	<h4>

		<?php comment_author_link( ); ?>

		<span class="meta"><?php comment_time(); ?> <?php _e('on', 'p2'); ?> <?php comment_date(); ?> <span class="actions"><a href="#comment-<?php comment_ID( ); ?>"><?php _e('Permalink', 'p2'); ?></a><?php echo comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => ' | ')) ?><?php edit_comment_link( __( 'Edit' , 'p2'), ' | ',''); ?></span><br /></span>

	</h4>

	<div class="commentcontent<?php if (current_user_can('edit_post', $comment->comment_post_ID)) echo(' comment-edit') ?>"  id="commentcontent-<?php comment_ID( ); ?>">

			<?php comment_text( ); ?>

	<?php if ( $comment->comment_approved == '0' ) : ?>

	<p><em><?php _e('Your comment is awaiting moderation.', 'p2') ?></em></p>

	<?php endif; ?>

	</div>

<?php	

}



function whatchat_up_to() {

	return (false === get_option('prologue_greeting')) ? __('Whatcha up to?') : get_option('prologue_greeting');

}



function prologue_the_title($before = '<h2>', $after = '</h2>') {

	

	global $post, $looping;

	$t = $post->post_title;

	

	if($looping == 0)

		return $t;

	$pos = 0;

	

	if(get_option('prologue_show_titles') != 1) 

		return false;

		

	$post->post_content = trim($post->post_content);

	$post->post_title = trim($post->post_title);

	$post->post_title = preg_replace('/\.\.\.$/','',$post->post_title);

	$post->post_title = str_replace("\n", ' ', $post->post_title );

	$post->post_title = str_replace('  ',' ', $post->post_title);

	$post->post_content = str_replace("\n", ' ', strip_tags($post->post_content) );

	$post->post_content = str_replace('  ',' ', $post->post_content);

	$post->post_content = trim($post->post_content);

	$post->post_title = trim($post->post_title);

	

	if( is_int( strpos($post->post_title, 'http') ) )  {

		$split = str_split( $post->post_content, strpos($post->post_content, 'http'));

		$post->post_content = $split[0];

		$split2 = str_split( $post->post_title, strpos($post->post_title, 'http'));

		$post->post_title = $split2[0];

	}

	$pos = @strpos( $post->post_content, $post->post_title );

	

	// these are for debugging

	/*

	error_log('content '.$post->post_content);

	error_log('title '.$post->post_title);

	error_log($pos);*/



	if( (false === $pos or $pos > 0) && $post->post_title != '') 

		echo (is_single()) ? $before.$t.$after : $before.'<a href="'.$post->guid.'">'.$t.'</a>'.$after;

}



function prologue_loop() {

	global $looping;

	$looping = ($looping === 1 ) ? 0 : 1;

}

add_action('loop_start', 'prologue_loop');

add_action('loop_end', 'prologue_loop');



function prologue_comment_widget_html( $comment, $size, $tdclass, $echocomment = true ) {

	if ( !$size = (int) $size )

		$size = 32;



	if ( $comment->comment_author == '' )

		$comment->comment_author = __('Anonymous', 'p2');

	$author = $comment->comment_author;

	$excerpt = wp_html_excerpt( $author, 20 );

	if ( $author != $excerpt )

		$author = $excerpt.'&hellip;';



	$avatar = get_avatar( $comment, $size );



	if ( $comment_author_url = $comment->comment_author_url ? clean_url( $comment->comment_author_url ) : '' ) {

		$avatar = "<a href='$comment_author_url' rel='nofollow'>$avatar</a>";

		// entitities in comment author are kept escaped in the db and tags are not allowed, so

		// no need of HTML escaping here

		$author = "<a href='$comment_author_url' rel='nofollow'>$author</a>";

	}



	$thiscomment  = '<tr><td title="' . attribute_escape( $comment->comment_author ) . '" class="recentcommentsavatar' . $tdclass . '" style="height:' . $size . 'px; width:' . $size . 'px">' . $avatar . '</td>';

	$thiscomment .= '<td class="recentcommentstext' . $tdclass . '">';



	$post_title = wp_specialchars( strip_tags( get_the_title( $comment->comment_post_ID ) ) );

	$excerpt = wp_html_excerpt( $post_title, 30 );

	if ( $post_title != $excerpt )

		$post_title = $excerpt.'&hellip;';



	$comment_content = strip_tags( $comment->comment_content );

	$excerpt = wp_html_excerpt( $comment_content, 50 );

	if ( $comment_content != $excerpt )

		$comment_content = $excerpt.'&hellip;';



	$comment_url = get_comment_link( $comment );



	// Only use the URL's #fragment if the comment is visible on the pgae.

	// Works by detecting if the comment's post is visible on the page... may break if P2 decides to do clever stuff with comments when paginated

	if ( @constant( 'DOING_AJAX' ) && isset( $_GET['vp'] ) && is_array( $_GET['vp'] ) && in_array( $comment->comment_post_ID, $_GET['vp'] ) ) {

		$comment_url = "#comment-{$comment->comment_ID}";

	} else {

		static $posts_on_page = false;

		if ( false === $posts_on_page ) {

			global $wp_query;



			$posts_on_page = array();

			foreach ( array_keys( $wp_query->posts ) as $k )

				$posts_on_page[$wp_query->posts[$k]->ID] = true;

		}



		if ( isset( $posts_on_page[$comment->comment_post_ID] ) )

			$comment_url = "#comment-{$comment->comment_ID}";

	}



	$thiscomment .= sprintf( __( "%s on <a href='%s' class='tooltip' title='%s'>%s</a>" , 'p2') . '</td></tr>', $author, $comment_url, attribute_escape($comment_content), $post_title );

	

	if ($echocomment)

		echo $thiscomment;

	else

		return $thiscomment;

}



function prologue_comment_frontpage( $comment, $args, $echocomment = true ) {

	$GLOBALS['comment'] = $comment;

	

	$depth = prologue_get_comment_depth( get_comment_ID() );

	$comment_text =  apply_filters( 'comment_text', $comment->comment_content );

	$comment_class = comment_class( $class = '', $comment_id = null, $post_id = null, $echo = false );

	$comment_time = get_comment_time();

	$comment_date = get_comment_date();

	$id = get_comment_ID();

	$avatar = prologue_get_avatar( $comment->user_id, $comment->comment_author_email, 32 );

	$author_link = get_comment_author_link();

	$reply_link = prologue_get_comment_reply_link(

        array('depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => ' | ', 'reply_text' => __('Reply', 'p2') ),

        $comment->comment_ID, $comment->comment_post_ID );

	$can_edit = current_user_can( 'edit_post', $comment->comment_post_ID );

	$edit_comment_url = get_edit_comment_link( $comment->comment_ID );

	$edit_link = $can_edit? " | <a class='comment-edit-link' href='$edit_comment_url' title='".attribute_escape(__('Edit comment', 'p2'))."'>".__('Edit', 'p2')."</a>" : '';

	$content_class = $can_edit? 'commentcontent comment-edit' : 'commentcontent';

	$awaiting_message = $comment->comment_approved == '0'? '<p><em>'.__('Your comment is awaiting moderation.', 'p2').'</em></p>' : '';

	$permalink = clean_url( get_comment_link() );

	$permalink_text = __('Permalink', 'p2');

	$date_time = sprintf( __('%s <em>on</em> %s', 'p2'),  get_comment_time(), get_comment_date() );

	$html = <<<HTML

<li $comment_class id="comment-$id">

    $avatar

    <h4>

        $author_link

        <span class="meta">

            $date_time

            <span class="actions"><a href="$permalink">$permalink_text</a> $reply_link $edit_link</span><br />

        </span>

    </h4>

    <div class="$content_class" id="commentcontent-$id">

        $comment_text

    </div>

HTML;

	if(get_comment_type() != 'comment')

		return false;

		

	if ($echocomment)

		echo $html;

	else

		return $html;

}



function tags_with_count( $format = 'list', $before = '', $sep = '', $after = '' ) {

	global $post;

	$posttags = get_the_tags($post->ID, 'post_tag');

	

	if ( !$posttags )

		return;

	

	foreach ( $posttags as $tag ) {

		if ( $tag->count > 1 && !is_tag($tag->slug) ) {

			$tag_link = '<a href="' . get_term_link($tag, 'post_tag') . '" rel="tag">' . $tag->name . ' (' . number_format_i18n( $tag->count ) . ')</a>';

		} else {

			$tag_link = $tag->name;

		}

		

		if ( $format == 'list' )

			$tag_link = '<li>' . $tag_link . '</li>';

		

		$tag_links[] = $tag_link;

	}

	

	echo $before . join( $sep, $tag_links ) . $after;

}



function latest_post_permalink() {

	global $wpdb;

	$sql = "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_date DESC LIMIT 1";

	$last_post_id = $wpdb->get_var($sql);

	$permalink = get_permalink($last_post_id);

	return $permalink;

}



function prologue_ajax_tag_search() {

	global $wpdb;

	$s = $_GET['q'];

	if ( false !== strpos( $s, ',' ) ) {

		$s = explode( ',', $s );

		$s = $s[count( $s ) - 1];

	}

	$s = trim( $s );

	if ( strlen( $s ) < 2 )

		die; // require 2 chars for matching

	

	$results = $wpdb->get_col( "SELECT t.name FROM $wpdb->term_taxonomy AS tt INNER JOIN $wpdb->terms AS t ON tt.term_id = t.term_id WHERE tt.taxonomy = 'post_tag' AND t.name LIKE ('%". like_escape( $wpdb->escape( $s ) ) . "%')" );

	echo join( $results, "\n" );

	exit;

}



function prologue_title_from_content( $content ) {

	

    static $strlen =  null;

    if ( !$strlen ) {

        $strlen = function_exists('mb_strlen')? 'mb_strlen' : 'strlen';

    }

    $max_len = 40;

    $title = $strlen( $content ) > $max_len? wp_html_excerpt( $content, $max_len ) . '...' : $content;

    $title = trim( strip_tags( $title ) );

    $title = str_replace("\n", " ", $title);



	//Try to detect image or video only posts, and set post title accordingly

	if ( !$title ) {

		if ( preg_match("/<object|<embed/", $content ) )

			$title = __('Video Post', 'p2');

		elseif ( preg_match( "/<img/", $content ) )

			$title = __('Image Post', 'p2');

		else

			$title = __('No Title', 'p2');

	}

    return $title;

}

if ( is_admin() && ( false === get_option('prologue_show_titles') ) ) add_option('prologue_show_titles', 1);



function prologue_inline_save() {

	check_ajax_referer( 'ajaxnonce', '_inline_edit' );

	if ( !is_user_logged_in() ) {

		die('<p>'.__('Error: not logged in.', 'p2').'</p>');

	}



	$post_id = $_POST['post_ID'];

	$post_id = substr( $post_id, strpos( $post_id, '-' ) + 1 );

	

	if ( !current_user_can( 'edit_post', $post_id )) {

		die('<p>'.__('Error: not allowed to edit post.', 'p2').'</p>');

	}

	

	$user_id = $current_user->ID;

	$post_content	= $_POST['content'];

	

	// preserve custom "big" titles



	$thepost = get_post($post_id);

	

	$clean_title = str_replace('&hellip;', '', $thepost->post_title);



	if( strpos($thepost->post_content, $clean_title ) !== 0 ) {

		$post_title = $thepost->post_title;

	} else {

		$post_title = prologue_title_from_content( $post_content );

	}



	$post = wp_update_post( array(

		'post_title'	=> $post_title,

		'post_content'	=> $post_content,

		'post_modified'	=> current_time('mysql'),

		'post_modified_gmt'	=> current_time('mysql', 1),

		'ID' => $post_id

	));

	

	$thepost = get_post( $post );



	echo apply_filters( 'the_content', $thepost->post_content );

	exit;

}



function prologue_inline_comment_save() {

	check_ajax_referer( 'ajaxnonce', '_inline_edit' );

	if ( !is_user_logged_in() ) {

		die('<p>'.__('Error: not logged in.', 'p2').'</p>');

	}



	$comment_id	= $_POST['comment_ID'];

	$comment_id = substr( $comment_id, strpos( $comment_id, '-' ) + 1);

	$this_comment = get_comment( $comment_id );

	

	if ( !current_user_can( 'edit_post', $this_comment->comment_post_ID ) ) {

		die('<p>'.__('Error: not allowed to edit this comment.', 'p2').'</p>');

	}

	

	$user_id = $current_user->ID;

	$comment_content = $_POST['comment_content'];

	

	$comment = wp_update_comment( array(

		'comment_content'	=> $comment_content,

		'comment_ID' => $comment_id

	));

	

	$thecomment = get_comment( $comment_id );

	echo apply_filters( 'comment_text', $thecomment->comment_content );

	exit;

}



function prologue_load_post() {

	check_ajax_referer( 'ajaxnonce', '_inline_edit' );

	if ( !is_user_logged_in() ) {

		die('<p>'.__('Error: not logged in.', 'p2').'</p>');

	}

	$post_id = $_GET['post_ID'];

	$post_id = substr( $post_id, strpos( $post_id, '-' ) + 1 );

	if ( !current_user_can( 'edit_post', $post_id ) ) {

		die('<p>'.__('Error: not allowed to edit post.', 'p2').'</p>');

	}

	$this_post = get_post( $post_id );

	echo $this_post->post_content ;

	exit;

}



function prologue_load_comment() {

	check_ajax_referer( 'ajaxnonce', '_inline_edit' );

	if ( !is_user_logged_in() ) {

		die('<p>'.__('Error: not logged in.', 'p2').'</p>');

	}

	$comment_id = attribute_escape($_GET['comment_ID']);

	$comment_id = substr( $comment_id, strpos( $comment_id, '-' ) + 1);

	$this_comment = get_comment($comment_id);

	$comment_content = $this_comment->comment_content;

	echo $comment_content;

	exit;

}



function prologue_new_post() {

	if( 'POST' != $_SERVER['REQUEST_METHOD'] || empty( $_POST['action'] ) || $_POST['action'] != 'prologue_new_post' ) {

	    die();

	}

	if ( !is_user_logged_in() ) {

		die('<p>'.__('Error: not logged in.', 'p2').'</p>');

	}

	if( !current_user_can( 'publish_posts' ) ) {

		die('<p>'.__('Error: not allowed to post.', 'p2').'</p>');

	}

	check_ajax_referer( 'ajaxnonce', '_ajax_post' );

	$user_id		= $current_user->user_id;

	$post_content	= $_POST['posttext'];

	$tags			= trim( $_POST['tags'] );

	if ( $tags == __('Tag it', 'p2') || $tags == 'Tag it' ) $tags = '';

	

    $post_title = prologue_title_from_content( $post_content );



	$post_id = wp_insert_post( array(

		'post_author'	=> $user_id,

		'post_title'	=> $post_title,

		'post_content'	=> $post_content,

		'tags_input'	=> $tags,

		'post_status'	=> 'publish'

	) );

	echo $post_id? $post_id : '0';

	exit;

}





function prologue_new_post_noajax() {

	if( 'POST' != $_SERVER['REQUEST_METHOD'] || empty( $_POST['action'] ) || $_POST['action'] != 'post' ) {

	    return;

	}

	if ( ! is_user_logged_in() )

		auth_redirect();

	if( !current_user_can( 'publish_posts' ) ) {

		wp_redirect( get_bloginfo( 'url' ) . '/' );

		exit;

	}



	check_admin_referer( 'new-post' );



	$user_id		= $current_user->user_id;

	$post_content	= $_POST['posttext'];

	$tags			= $_POST['tags'];



	$title = prologue_title_from_content( $post_content );



	$post_id = wp_insert_post( array(

		'post_author'	=> $user_id,

		'post_title'	=> $post_title,

		'post_content'	=> $post_content,

		'tags_input'	=> $tags,

		'post_status'	=> 'publish'

	) );



	wp_redirect( get_bloginfo( 'url' ) . '/' );

	exit;

}





function prologue_new_comment() {

	if( 'POST' != $_SERVER['REQUEST_METHOD'] || empty( $_POST['action'] ) || $_POST['action'] != 'prologue_new_comment' ) {

	    die();

	}

	

	check_ajax_referer( 'ajaxnonce', '_ajax_post' );

	

	$comment_content = isset( $_POST['comment'] )? trim( $_POST['comment'] ) : null;

	$comment_post_ID = isset( $_POST['comment_post_ID'] )? trim( $_POST['comment_post_ID'] ) : null;

	$user = wp_get_current_user();

	if ( $user->ID ) {

		if ( empty( $user->display_name ) )

			$user->display_name = $user->user_login;

		$comment_author       = $user->display_name;

		$comment_author_email = $user->user_email;

		$comment_author_url   = $user->user_url;

		$comment_author_url   = $user->user_url;

		$user_ID 			  = $user->ID;

		if ( current_user_can( 'unfiltered_html' ) ) {

		    $unfiltered_html = isset($_POST['_wp_unfiltered_html_comment'])? $_POST['_wp_unfiltered_html_comment'] : '';

			if ( wp_create_nonce( 'unfiltered-html-comment_' . $comment_post_ID) != $unfiltered_html ) {

				kses_remove_filters(); // start with a clean slate

				kses_init_filters(); // set up the filters

			}

		}

	} else {

		if ( get_option('comment_registration') ) {

		    die('<p>'.__('Error: you must be logged in to post a comment.', 'p2').'</p>');

		}

	}



	$comment_type = '';



	if ( get_option( 'require_name_email' ) && !$user->ID ) {

		if ( strlen($comment_author_email) < 6 || '' == $comment_author ) {

			die('<p>'.__('Error: please fill the required fields (name, email).', 'p2').'</p>');

		} elseif ( !is_email( $comment_author_email ) ) {

		    die('<p>'.__('Error: please enter a valid email address.', 'p2').'</p>');

		}

	}



	if ( '' == $comment_content ) {

	    die('<p>'.__('Error: Please type a comment.', 'p2').'</p>');

	}

	

	$comment_parent = isset( $_POST['comment_parent'] ) ? absint( $_POST['comment_parent'] ) : 0;



	$commentdata = compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID' );



	$comment_id = wp_new_comment( $commentdata );

	$comment = get_comment( $comment_id );

	if ( !$user->ID ) {

		setcookie('comment_author_' . COOKIEHASH, $comment->comment_author, time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);

		setcookie('comment_author_email_' . COOKIEHASH, $comment->comment_author_email, time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);

		setcookie('comment_author_url_' . COOKIEHASH, clean_url($comment->comment_author_url), time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);

	}

	if ($comment)

		echo $comment_id;

	else 

		echo __("Error: Unknown error occured. Comment not posted.", 'p2');

    exit;

}



function prologue_latest_comments() {

	global $wpdb, $comments, $comment, $max_depth, $depth, $user_login, $user_ID, $user_identity;

	

	$number = 10; //max amount of comments to load

	$load_time = $_GET['load_time'];

	$lc_widget = $_GET['lcwidget'];

	$visible_posts =  (array)$_GET['vp'];

	

	if ( get_option('thread_comments') )

		$max_depth = get_option('thread_comments_depth');

	else

		$max_depth = -1;



	//Widget info

	if ( !isset($options) )

		$options = get_option('widget_recent_comments');

	$size = $options[ 'avatar_size' ] ? $options[ 'avatar_size' ] : 32;

	

	//get new comments

	if ($user_ID) {

		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE (comment_approved = '1' OR ( user_id = %d AND comment_approved = '0' ))  AND comment_date_gmt > %s ORDER BY comment_date_gmt DESC LIMIT $number", $user_ID, $load_time));

	} else if ( empty($comment_author) ) {

		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_approved = '1' AND comment_date_gmt > %s ORDER BY comment_date_gmt DESC LIMIT $number", $load_time));

	} else {

		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE (comment_approved = '1' OR ( comment_author = %s AND comment_author_email = %s AND comment_approved = '0' ) ) AND comment_date_gmt > %s ORDER BY comment_date_gmt DESC LIMIT $number", $comment_author, $comment_author_email, $load_time));

	}

	$number_of_new_comments = count($comments);



    $prepare_comments = array();

	if ($number_of_new_comments > 0) {

		foreach ($comments as $comment) {



			// Setup comment html if post is visible

			$comment_html = '';

			if ( in_array( $comment->comment_post_ID, $visible_posts ) ) {

				$comment_html = prologue_comment_frontpage($comment, array('max_depth' => $max_depth, 'before' => ' | '), $depth, false);

			}



			// Setup widget html if widget is visible

			$comment_widget_html = '';

			if ( $lc_widget ) {

				$comment_widget_html = prologue_comment_widget_html( $comment, $size, 'top', false );

			}

			

			$prepare_comments[] = array( "id" => $comment->comment_ID, "postID" => $comment->comment_post_ID, "commentParent" =>  $comment->comment_parent,

				"html" => $comment_html, "widgetHtml" => $comment_widget_html );

		}

		

		$json_data = array("numberofnewcomments" => $number_of_new_comments, "comments" => $prepare_comments, "lastcommenttime" => gmdate( 'Y-m-d H:i:s' ));

		echo json_encode( $json_data );

	} else { // No new comments

        echo('0');

	}

	exit;

}



function prologue_latest_posts() {

	$load_time = $_GET['load_time'];

	$frontpage = $_GET['frontpage'];

	$num_posts = 10; //max amount of posts to load

	$number_of_new_posts = 0;

	$prologue_query = new WP_Query('showposts=' . $num_posts . '&post_status=publish');

	ob_start();

	while ($prologue_query->have_posts()) : $prologue_query->the_post();

	    $current_user_id = get_the_author_ID( );

		if ( get_gmt_from_date( get_the_time( 'Y-m-d H:i:s' ) ) <=  $load_time ) continue;

		$number_of_new_posts++;

		if ( $frontpage ) {

?>

<li id="prologue-<?php the_ID(); ?>" class="newupdates user_id_<?php the_author_ID( ); ?>">

    <?php echo prologue_get_avatar( $current_user_id, get_the_author_email( ), 48 ); ?>

    <h4>

		<?php the_author_posts_link( ); ?>

		<span class="meta">

		    <?php printf( __('%s <em>on</em> %s', 'p2'),  get_the_time(), get_the_time( get_option('date_format') ) ); ?> |

			<?php comments_popup_link( '0', '1', '%' ); ?>

			

			<span class="actions">

				<a href="<?php the_permalink( ); ?>" class="thepermalink"><?php _e('Permalink', 'p2'); ?></a>

			<?php if (function_exists('post_reply_link'))

				echo post_reply_link(array('before' => ' | ', 'reply_text' => __('Reply', 'p2'), 'add_below' => 'prologue'), get_the_id()); ?>

			<?php if (current_user_can('edit_post', get_the_id())) { ?>

			|  <a href="<?php echo (get_edit_post_link( get_the_id() ))?>" class="post-edit-link" rel="<?php the_ID(); ?>"><?php _e('Edit', 'p2'); ?></a>

			<?php } ?>

			</span>

			<br />

			<?php tags_with_count( '', __( 'Tags:' , 'p2').' ', ', ', ' ' ); ?>

		</span>

	</h4>

	<div class="postcontent<?php if (current_user_can( 'edit_post', get_the_id() )) {?> editarea<?php } ?>" id="content-<?php the_ID(); ?>"><?php the_content( __( '(More ...)' , 'p2') ); ?></div> <!-- // postcontent -->

	<div class="bottom_of_entry">&nbsp;</div>

</li>

<?php

}

    endwhile;

    $posts_html = ob_get_contents();

    ob_end_clean();

    if ( $number_of_new_posts == 0 ) {

    	echo '0';

    } else {

    	$json_data = array("numberofnewposts" =>$number_of_new_posts, "html" => $posts_html, "lastposttime" => gmdate( 'Y-m-d H:i:s' ));

    	echo json_encode( $json_data );

    }

    exit;

}



/* Recent comments with avatars */

function prologue_widget_recent_comments_avatar( $args ) {

	global $wpdb, $comments, $comment;

	extract($args, EXTR_SKIP);

	if ( !isset($options) )

		$options = get_option('widget_recent_comments');

	$title = empty($options['title']) ? __('Recent Comments', 'p2') : $options['title'];

	if ( !$number = (int) $options['number'] )

		$number = 5;

	else if ( $number < 1 )

		$number = 1;

	else if ( $number > 15 )

		$number = 15;



	if ( !$comments = wp_cache_get( 'recent_avatar_comments', 'widget' ) ) {

		$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_approved = '1' ORDER BY comment_date_gmt DESC LIMIT $number");

		wp_cache_add( 'recent_avatar_comments', $comments, 'widget' );

	}

	$size = $options[ 'avatar_size' ] ? $options[ 'avatar_size' ] : 18;

	?>

	<?php echo $before_widget; ?>

		<?php echo $before_title . wp_specialchars( $title ) . $after_title; ?>

		<table class='recentcommentsavatar' cellspacing='0' cellpadding='0' border='0' id="recentcommentstable"><?php

		$tdclass = 'top';

		if ( $comments ) : foreach ($comments as $comment) :

			prologue_comment_widget_html($comment, $size, $tdclass, true);

		endforeach; endif;?></table>

	<?php echo $after_widget; ?>

<?php

}



if(!function_exists('wp_delete_recent_comments_avatar_cache')) {

	function wp_delete_recent_comments_avatar_cache() {

		wp_cache_delete( 'recent_avatar_comments', 'widget' );

	}

	add_action( 'comment_post', 'wp_delete_recent_comments_avatar_cache' );

	add_action( 'wp_set_comment_status', 'wp_delete_recent_comments_avatar_cache' );

}



function prologue_widget_recent_comments_avatar_control() {

	$options = $newoptions = get_option('widget_recent_comments');

	if ( $_POST["recent-comments-submit"] ) {

		$newoptions['title'] = strip_tags(stripslashes($_POST["recent-comments-title"]));

		$newoptions['number'] = (int) $_POST["recent-comments-number"];

		$newoptions['avatar_size'] = (int) $_POST["recent-comments-avatar-size"];

		$newoptions['avatar_bg'] = preg_replace('/[^a-z0-9#]/', '', $_POST["recent-comments-avatar-bg"] );

		$newoptions['text_bg'] = preg_replace('/[^a-z0-9#]/i', '', $_POST["recent-comments-text-bg"] );

	}

	if ( print_r( $options, 1 ) != print_r( $newoptions, 1 ) ) {

		$options = $newoptions;

		update_option('widget_recent_comments', $options);

		wp_delete_recent_comments_cache(); // If user selects "No Avatars", the core recent comments widget is used, so we need to clear that cache too.

		wp_delete_recent_comments_avatar_cache();

	}

	$title = $options['title'];

	$avatar_bg = $options[ 'avatar_bg' ];

	$text_bg   = $options[ 'text_bg' ];

	$avatar_size = $options[ 'avatar_size' ] == '' ? '48' : (int) $options[ 'avatar_size' ];

	if ( !$number = (int) $options['number'] )

		$number = 5;

	else if ( $number < 1 )

		$number = 1;

	else if ( $number > 15 )

		$number = 15;

?>

			<p><label for="recent-comments-title"><?php _e('Title:', 'p2'); ?> <input id="recent-comments-title" name="recent-comments-title" type="text" class="widefat" value="<?php echo attribute_escape($title); ?>" /></label></p>

			<p><label for="recent-comments-number"><?php _e('Number of comments to show:', 'p2'); ?> <input style="width: 25px; text-align: center;" id="recent-comments-number" name="recent-comments-number" type="text" value="<?php echo $number; ?>" /></label> <small><?php _e('(at most 15)', 'p2'); ?></small></p>

			<p><label for="recent-comments-avatar-size"><?php _e('Avatar Size (px):', 'p2'); ?> <select name='recent-comments-avatar-size'>

			<option value='1'<?php echo 1 == $avatar_size ? ' selected' : ''; ?>><?php _e( 'No Avatars' , 'p2'); ?></option>

			<option value='16'<?php echo 16 == $avatar_size ? ' selected' : ''; ?>>16x16</option>

			<option svalue='32'<?php echo 32 == $avatar_size ? ' selected' : ''; ?>>32x32</option>

			<option value='48'<?php echo 48 == $avatar_size ? ' selected' : ''; ?>>48x48</option>

			<option value='96'<?php echo 96 == $avatar_size ? ' selected' : ''; ?>>96x96</option>

			<option value='128'<?php echo 128 == $avatar_size ? ' selected' : ''; ?>>128x128</option>

			</select></label></p>

			<p><label for="recent-comments-avatar-bg"><?php _e('Avatar background color:', 'p2'); ?> <input style="width: 50px;" id="recent-comments-avatar-bg" name="recent-comments-avatar-bg" type="text" value="<?php echo $avatar_bg; ?>" /></label></p>

			<p><label for="recent-comments-text-bg"><?php _e('Text background color:', 'p2'); ?> <input style="width: 50px;" id="recent-comments-text-bg" name="recent-comments-text-bg" type="text" value="<?php echo attribute_escape($text_bg); ?>" /></label></p>



			<input type="hidden" id="recent-comments-submit" name="recent-comments-submit" value="1" />

<?php

}



function prologue_widget_recent_comments_avatar_style() {

	$options = get_option('widget_recent_comments');

	$avatar_bg = $options[ 'avatar_bg' ] == '' ? '' : 'background: ' . $options[ 'avatar_bg' ] . ';';

	$text_bg = $options[ 'text_bg' ] == '' ? '' : 'background: ' . $options[ 'text_bg' ] . ';';

	$style = "

<style type='text/css'>

table.recentcommentsavatar img.avatar { border: 0px; margin:0; }

table.recentcommentsavatar a {border: 0px !important; background-color: transparent !important}

td.recentcommentsavatartop {padding:0px 0px 1px 0px;

							margin:   0px;

							{$avatar_bg} }

td.recentcommentsavatarend {padding:0px 0px 1px 0px;

							margin:0px;

							{$avatar_bg} }

td.recentcommentstexttop {

							{$text_bg} border: none !important; padding:0px 0px 0px 10px;}

td.recentcommentstextend {

							{$text_bg} border: none !important; padding:0px 0px 2px 10px;}

</style>";

	echo $style;

}



function prologue_widget_recent_comments_avatar_register() {

	$options = get_option('widget_recent_comments');

	if( isset( $options[ 'avatar_size' ] ) && $options[ 'avatar_size' ] == 1 && is_admin() == false )

		return;

	$class = array('classname' => 'widget_recent_comments');

	wp_register_sidebar_widget('recent-comments', __('Recent Comments', 'p2'), 'prologue_widget_recent_comments_avatar', $class);

	wp_register_widget_control('recent-comments', __('Recent Comments', 'p2'), 'prologue_widget_recent_comments_avatar_control' );



	if ( is_active_widget('prologue_widget_recent_comments_avatar') )

		add_action('wp_head', 'prologue_widget_recent_comments_avatar_style');

}

add_action('init', 'prologue_widget_recent_comments_avatar_register', 10);



//Search related Functions



function search_comments_distinct( $distinct ) {

	global $wp_query;

	if (!empty($wp_query->query_vars['s'])) {

		return 'DISTINCT';

	}

}

add_filter('posts_distinct', 'search_comments_distinct');



function search_comments_where( $where ) {

	global $wp_query, $wpdb;

	if (!empty($wp_query->query_vars['s'])) {

			$or = " OR ( comment_post_ID = ".$wpdb->posts . ".ID  AND comment_approved =  '1' AND comment_content LIKE '%" . like_escape( $wpdb->escape($wp_query->query_vars['s'] ) ) . "%') ";

  			$where = preg_replace( "/\bor\b/i", $or." OR", $where, 1 );

	}

	return $where;

}

add_filter('posts_where', 'search_comments_where');



function search_comments_join( $join ) {

	global $wp_query, $wpdb, $request;

	if (!empty($wp_query->query_vars['s'])) {

		$join .= " LEFT JOIN $wpdb->comments ON ( comment_post_ID = ID  AND comment_approved =  '1')";

	}

	return $join;

}

add_filter('posts_join', 'search_comments_join');



function get_search_query_terms() {

	$search = get_query_var('s');

	$search_terms = get_query_var('search_terms');

	if ( !empty($search_terms) ) {

		return $search_terms;

	} else if ( !empty($search) ) {

		return array($search);

	}

	return array();

}



function hilite( $text ) {

	$query_terms = array_filter( array_map('trim', get_search_query_terms() ) );

	foreach ( $query_terms as $term ) {

	    $term = preg_quote( $term, '/' );

		if ( !preg_match( '/<.+>/', $text ) ) {

			$text = preg_replace( '/(\b'.$term.'\b)/i','<span class="hilite">$1</span>', $text );

		} else {

			$text = preg_replace( '/(?<=>)([^<]+)?(\b'.$term.'\b)/i','$1<span class="hilite">$2</span>', $text );

		}

	}

	return $text;

}



function hilite_tags( $tags ) {

	$query_terms = array_filter( array_map('trim', get_search_query_terms() ) );

	// tags are kept escaped in the db

	$query_terms = array_map( 'wp_specialchars', $query_terms );

	foreach( array_filter((array)$tags) as $tag ) {

	    if ( in_array( trim($tag->name), $query_terms ) ) {

	        $tag->name ="<span class='hilite'>". $tag->name . "</span>";

	    }

	}

	return $tags;

}



// Highlight text and comments:

add_filter('the_content', 'hilite');

add_filter('get_the_tags', 'hilite_tags');

add_filter('the_excerpt', 'hilite');

add_filter('comment_text', 'hilite');



function iphone_css() {

if ( strstr( $_SERVER['HTTP_USER_AGENT'], 'iPhone' ) or isset($_GET['iphone']) && $_GET['iphone'] ) { ?>

<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/> 

<style type="text/css">

#header_img img, #sidebar, #postbox .avatar {

	display: none;

}

#header {

	margin: 0;

	padding: 0;

}

#header .sleeve {

	padding: 0;

	margin: 0;

	width: 100%;

}

#header h1, #header small {

	display: block;

	width: 100%;

}

#header h1 {

	padding-left: 16px;

	margin-bottom: 2px;

}



#main h2 .controls {

	display: none;

}

.actions {

	clear: both;

	display: block;

	position: static !important;

	text-align: right;

	height: 1.1em;

	top: 0px !important;

	margin-top: 6px;

	margin-bottom: -1.8em;

}

.actions a { font-size: 1.2em; padding: 6px; }

.meta {

	margin-top: 6px;

	line-height: .8em;

	width: 100%;

}

div.postcontent, div.commentcontent {

	margin-left: 30px;

}

#main h4 {

	line-height: 1.1em;

	clear: both;

	margin-bottom: .5em;

}

.avatar {

position: relative;

left: 0px;

top: 5px;

margin-bottom: -20px;

}

#main #respond.replying, #main .commentlist li #respond.replying  {

position: absolute;

width: 100%;

height: 100%;	

	margin-left: 0 !important;

	z-index: 1000;

	left: 0px !important;

}

.single #main .postcontent {

	clear: both;

	margin: 0;

	padding: 0;

}

li #respond textarea {

	width: 80%;

	height: 80%;

}

#main h4 {

	position: relative;

	margin-left: 30px;

}

h1 a {

	display: block;

	width: 295px;

	font-family: Helvetica;

}

#footer {

	width: 100%;

	font-size: 8px;

	min-width: 0;

}

#main ul.commentlist, #main ul.commentlist ul {

	margin-left: 20px !important;

}



#wrapper {

width: 100%;

	

	min-width: 0;

	margin: 0;

	padding: 0;

	overflow: visible;

	position: static;

}

.avatar {

	width: 20px;

	height: 20px;

}

.sleeve_main {

width: 100%;

	margin: 0;

}

#header {

	padding: 0;

	margin: 0;

	width: 100%;

}



#main {

	margin: 0 10px;

	padding: 0;

	float: none;

}

#main ul#postlist ul li {

	margin-left: 0;

}

h1 {

	font-size: 2em;

	font-family: Georgia, "Times New Roman", serif;

	margin-left: 0;

	margin-top: 5px;

	margin-bottom: 10px;

	padding: 0;

}



h2 {

	font-size: 1.2em;

	font-weight: bold;

	color: #555;

}



#postbox form {

	padding: 5px;

}



#postbox textarea#posttext {

	height: 50px;

	border: 1px solid #c6d9e9;

	margin-bottom: 10px;

	padding: 2px;

	font: 1.4em/1.2em "Lucida Grande",Verdana,"Bitstream Vera Sans",Arial,sans-serif;

}

#postbox input#tags,  #commentform #comment {

	font-size: 1.2em;

	padding: 2px;

	border: 1px solid #c6d9e9;

	width: 300px;

	margin-left: 0;

}

#postbox {

	margin: 0;

	padding: 0;

}

#postbox label {

	color: #333;

	display: block;

	font-size: 1.2em;

	margin-bottom: 4px;

	margin-left: 0;

	font-weight: bold;

}

#postbox .inputarea {

	padding-left: 0;

}



#notify {

	width: 70%;

left: 15%;

top: 30%;

}

#postbox input#submit {

	font-size: 1.2em;



	margin-top: 5px;

}



#main ul {

	list-style: none;

	margin-top: 16px;

	margin-left: 0;

}



#wpcombar {

	display: none;

}

body {

	padding-top: 0 !important;

}

</style>

<?php } }

add_action('wp_head', 'iphone_css');



/*

	Modified to replace query string with blog url in output string

*/

function prologue_get_comment_reply_link( $args = array(), $comment = null, $post = null ) {

	global $user_ID;



	$defaults = array('add_below' => 'comment', 'respond_id' => 'respond', 'reply_text' => __('Reply', 'p2'),

		'login_text' => __('Log in to Reply', 'p2'), 'depth' => 0, 'before' => '', 'after' => '');



	$args = wp_parse_args($args, $defaults);

	if ( 0 == $args['depth'] || $args['max_depth'] <= $args['depth'] )

		return;



	extract($args, EXTR_SKIP);



	$comment = get_comment($comment);

	$post = get_post($post);



	if ( 'open' != $post->comment_status )

		return false;



	$link = '';



	$reply_text = wp_specialchars( $reply_text );



	if ( get_option('comment_registration') && !$user_ID )

		$link = '<a rel="nofollow" href="' . site_url('wp-login.php?redirect_to=' . urlencode( get_permalink() ) ) . '">' . wp_specialchars( $login_text ) . '</a>';

	else

		$link = "<a rel='nofollow' class='comment-reply-link' href='". get_permalink($post). "#" . urlencode( $respond_id ) . "' onclick='return addComment.moveForm(\"" . js_escape( "$add_below-$comment->comment_ID" ) . "\", \"$comment->comment_ID\", \"" . js_escape( $respond_id ) . "\", \"$post->ID\")'>$reply_text</a>";

	return apply_filters('comment_reply_link', $before . $link . $after, $args, $comment, $post);

}



function prologue_comment_depth_loop( $comment_id, $depth )  {

	$comment = get_comment( $comment_id );

	if ( $comment->comment_parent != 0 ) {

		return prologue_comment_depth_loop( $comment->comment_parent, $depth + 1 );

	}

	return $depth;

}



function prologue_get_comment_depth( $comment_id ) {

	return prologue_comment_depth_loop( $comment_id, 1 );

}



function prologue_comment_depth( $comment_id ) {

	echo prologue_get_comment_depth( $comment_id );

}



function prologue_navigation() { ?>

	<div class="navigation"><p>

	<?php if(!is_single()) { ?>

   		<?php posts_nav_link(' | ', __('&larr;&nbsp;Newer&nbsp;Posts', 'p2'), __('Older&nbsp;Posts&nbsp;&rarr;', 'p2')); ?>

	<?php } else { ?>

		<?php previous_post_link('%link', __('&larr;&nbsp;Older&nbsp;Posts', 'p2') ) ?> | <?php next_post_link('%link', __('Newer&nbsp;Posts&nbsp;&rarr;', 'p2')) ?>

	<?php } ?>

</p></div>

<?php }



function prologue_poweredby_link() {

    return apply_filters( 'prologue_poweredby_link',

        sprintf( __('<strong>%1$s</strong> powered by %2$s.', 'p2'),

        get_bloginfo('name'), '<a href="http://wordpress.org/" rel="generator">WordPress</a>' ) );

}



if ( defined('IS_WPCOM') && IS_WPCOM ) {

    add_filter( 'prologue_poweredby_link', returner('<a href="http://wordpress.com/">'.__('Blog at WordPress.com', 'p2').'.</a>') );

}

