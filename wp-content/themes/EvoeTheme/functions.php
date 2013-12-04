<?php
	include_once (TEMPLATEPATH .   '/global/inc/admin_subTheme_page.php');
	include_once (TEMPLATEPATH .   '/global/inc/flickr_widget.php');
	include_once (TEMPLATEPATH .   '/global/inc/twitter_widget.php');
	include_once (TEMPLATEPATH .   '/global/inc/mobile_device_detect.php');
	
	load_theme_textdomain( 'evoeTheme', TEMPLATEPATH.'/global/languages' );
	
	$locale = get_locale();
	$locale_file = TEMPLATEPATH."/global/languages/$locale.php";
	if ( is_readable($locale_file) )
		require_once($locale_file);	
	
	// Mobile switch
	function mobile_switch( $template )
	{
		$mobile = mobile_device_detect();
		
		/*if ( $mobile == true ) //&& $mobile_browser == ( $iphone || $android || $blackberry )
		{
			if(  $template == 'index' )
			{
				include_once (TEMPLATEPATH .   '/global/mobile/index.php');
			}
			elseif( $template == 'page' )
			{
				include_once (TEMPLATEPATH .   '/global/mobile/page.php');
			}
			elseif( $template == 'single' )
			{
				include_once (TEMPLATEPATH .   '/global/mobile/single.php');
			}
			elseif( $template == 'archive' )
			{
				include_once (TEMPLATEPATH .   '/global/mobile/archive.php');
			}
			elseif( $template == 'search' )
			{
				include_once (TEMPLATEPATH .   '/global/mobile/search.php');
			}
			elseif( $template == '404' )
			{
				include_once (TEMPLATEPATH .   '/global/mobile/404.php');
			}
			
			return true;
		}*/
		
		return false;
	}
	
	// Mobile options
	function get_mobile_header()
	{
		include_once (TEMPLATEPATH .   '/global/mobile/header.php');
	}
	function get_mobile_footer()
	{
		include_once (TEMPLATEPATH .   '/global/mobile/footer.php');
	}
	
	// Max length
	function limit_chars($content, $length)
	{
		$content = strip_shortcodes( $content );
		$content = apply_filters('the_content', $content);
		$content = strip_tags($content);
	
		if(strlen($content) > $length)
		{
			$content = substr($content, 0, $length);
			$content = substr($content, 0, strrpos($content, " "))."...";
		}
		print $content;
	}
	
	// Recuperar a primeira imagem anexada a um post
	function the_thumb($post, $size = "medium", $add = "")
	{
		global $wpdb;
		
		$thumb = $wpdb->get_row("SELECT ID, post_title FROM {$wpdb->posts} WHERE post_parent = {$post} AND post_mime_type LIKE 'image%' ORDER BY menu_order");
		
		if(!empty($thumb))
		{
			$image = image_downsize($thumb->ID, $size);
			echo '<img src="'. $image[0] .'" alt="'. $thumb->post_title .'"'. $add .' />';
		}
	}
	
	// my_register_sidebar
	function my_register_sidebar($name)
	{
		register_sidebar(array(
			'name'          => $name,
			'before_widget' => '<div class="wrapper"><div class="outer"><div class="inner"><div class="widget %2$s %1$s" id="%1$s">',
			'after_widget' => '</div></div></div></div></div></div>',
			'before_title' => '<div class="bigTitle"><div class="previewPattern"></div><h4>',
			'after_title' => '<span class="tab"></span></h4></div><div class="tabs"><div class="tab">',
		));
	}

	// cadastrar widgets padroes
	function my_register_widget($widget, $name)
	{
		if ($widget == 'WP_Widget_Search')
			$classname = 'widget_search';
		elseif ($widget == 'WP_Widget_Categories' )
			$classname = 'widget_categories';

		the_widget($widget, "title=".$name, array(
			'before_widget' => "<div class='wrapper'><div class='outer'><div class='inner'><div class='widget {$classname}' id='{$classname}'>",
			'after_widget' => "</div></div></div></div></div></div>",
			'before_title' => "<div class='bigTitle'><div class='previewPattern'></div><h4>",
			'after_title'  => "<span class='tab'></span></h4></div><div class='tabs'><div class='tab'>",		
		));
	}
	
	// cadastrar os sidebars
	if( function_exists( 'my_register_sidebar' ) )
	{
		my_register_sidebar('home');
		my_register_sidebar('page');
		my_register_sidebar('single');
		my_register_sidebar('archive');
	}
	
	// Separating pings from comments_number
	add_filter('get_comments_number', 'comment_count', 0);
	function comment_count( $count )
	{
        if (!is_admin())
		{
			global $id;
			$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
			return count($comments_by_type['comment']);
        } 
		else 
		{
			return $count;
        }
	}
	
	// Custom pings
	function mytheme_pings($comment, $args, $depth)
	{
		$GLOBALS['comment'] = $comment;
		
		echo '<li>'. get_comment_author_link() .'</li>';
	}
	
	// Custom comment
	function mytheme_comment($comment, $args, $depth)
	{
		$GLOBALS['comment'] = $comment;
		
		if ( $depth == 1 ) { ?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">	
			<div>
				<div class="authorComment">
					<?php echo get_avatar($comment, $size='80'); ?>
					<p class="autor"><?php print get_comment_author_link() . ' <span>' . get_comment_date( 'j' ) . ' de ' . get_comment_date( 'F' ) . '</span>'; ?></p>
				</div>
			</div>
			<div class="comentario">
				<span class="seta"></span>
				<?php if ($comment->comment_approved == '0') : ?>
					<p><?php _e('Seu comentário está aguardando a administração.') ?></p>
				<?php else: ?>
					<?php comment_text() ?>
				<?php endif; ?>
				<?php if( comment_reply_link(array_merge( $args, array('reply_text' => __('Responder', 'evoeTheme'), 'depth' => $depth, 'max_depth' => $args['max_depth']))) != '' ) { comment_reply_link(array_merge( $args, array('reply_text' => __('Responder', 'evoeTheme'), 'depth' => $depth, 'max_depth' => $args['max_depth']))); } else { echo '<div class="reply-link"></div>'; } ?>
			</div>
			<div class="clear"></div>
			
		<?php } else { ?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
			<div class="comment-reply">
				<?php if ($comment->comment_approved == '0') : ?>
					<p><?php _e('Seu comentário está aguardando a administração.') ?></p>
				<?php else : ?>
					<?php comment_text() ?>
				<?php endif; ?>
				<?php comment_reply_link(array_merge( $args, array('reply_text' => __('Responder', 'evoeTheme'), 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				<div class="autorResposta">
					<p class="nomeData"><?php print get_comment_author_link() . ' <span>' . get_comment_date( 'j' ) . ' de ' . get_comment_date( 'F' ) . '</span>'; ?></p>
					<?php echo get_avatar($comment, $size='40'); ?>
				</div>
				<div class="clear"></div>
			</div>
		<?php }
	}

?>
