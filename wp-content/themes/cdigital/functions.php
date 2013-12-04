<?php
   //theme support
   add_theme_support( 'post-thumbnails' );

	// includes
	include_once(TEMPLATEPATH . '/bp/bp-functions.php');
	include_once(TEMPLATEPATH . '/global/inc/limit_chars.php');
	include_once(TEMPLATEPATH . '/global/inc/the_thumb.php');
	include_once(TEMPLATEPATH . '/global/inc/widget_destaque.php');	
	include_once(TEMPLATEPATH . '/global/inc/widget_atividades.php');
	include_once(TEMPLATEPATH . '/global/inc/widget_blogs.php');
	include_once(TEMPLATEPATH . '/global/inc/widget_customPosts.php');
	include_once(TEMPLATEPATH . '/global/inc/widget_login.php');
	include_once(TEMPLATEPATH . '/global/inc/widget_map.php');
	include_once(TEMPLATEPATH . '/global/inc/widget_comentarios.php');
	include_once(TEMPLATEPATH . '/global/inc/widget_blogadas.php');
		
	// Filtra as atividades em todo site
	function my_custom_activities ($a, $activities)
	{
		foreach ( $activities->activities as $key => $activity ) 
		{
			//new_member is the type name (component is 'profile')
			if ( $activity->type == 'new_member' )
			{
				unset( $activities->activities[$key] );
			
				$activities->activity_count = $activities->activity_count - 1;
				$activities->total_activity_count = $activities->total_activity_count - 1;
				$activities->pag_num = $activities->pag_num - 1;
			}
			elseif( $activity->type == 'created_group' )
			{
				unset( $activities->activities[$key] );
			
				$activities->activity_count = $activities->activity_count - 1;
				$activities->total_activity_count = $activities->total_activity_count - 1;
				$activities->pag_num = $activities->pag_num - 1;
			}
			elseif( $activity->type == 'joined_group' )
			{
				unset( $activities->activities[$key] );
			
				$activities->activity_count = $activities->activity_count - 1;
				$activities->total_activity_count = $activities->total_activity_count - 1;
				$activities->pag_num = $activities->pag_num - 1;
			}
			elseif( $activity->type == 'friendship_accepted' || $activity->type == 'friendship_created' )
			{
				unset( $activities->activities[$key] );
			
				if( $activity->type == 'friendship_accepted' && $activity->type == 'friendship_created' )
				{
					$activities->activity_count = $activities->activity_count - 2;
					$activities->total_activity_count = $activities->total_activity_count - 2;
					$activities->pag_num = $activities->pag_num - 2;
				}
				else
				{
					$activities->activity_count = $activities->activity_count - 1;
					$activities->total_activity_count = $activities->total_activity_count - 1;
					$activities->pag_num = $activities->pag_num - 1;
				}
			}
		}
	
		/* Renumber the array keys to account for missing items */
		$activities_new = array_values( $activities->activities );
		$activities->activities = $activities_new;
	
		return $activities;
    }
	
    add_action('bp_has_activities','my_custom_activities', 10, 2 );
	
	// Modifica a barra do admin
	function bp_my_admin_bar()
	{		
		global $bp, $wpdb, $current_blog, $doing_admin_bar;
		 
		$doing_admin_bar = true;
		 
		if ( (int) get_site_option( 'hide-loggedout-adminbar' ) && !is_user_logged_in() )
		return false;
		 
		echo '<div id="wp-admin-bar">'
			.'<div class="padder">'
			.'<div class="middle">'
			.'<h4>Painel rápido &raquo;</h4>';
		 
		echo '<ul class="main-nav">';
		 
		// **** Do bp-adminbar-menus Actions ********
		do_action( 'bp_adminbar_menus' );
		 
		echo '</ul>'
			.'</div>'
			.'</div>'
			.'</div>';
	}	
	
	remove_action('wp_footer', 'bp_core_admin_bar', 8);
	add_action( 'wp_footer', 'bp_my_admin_bar', 8 );
	
	// my_register_sidebar
	function my_register_sidebar($name)
	{
		register_sidebar(
			array(
				'name'			=> $name,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'	=> '</div>',
				'before_title'	=> '<h2 class="widgettitle">',
				'after_title'	=> '</h2>'
			)
		);
	}
	
	// cadastrar sidebars
	if(function_exists('register_sidebar'))
	{
		my_register_sidebar('left-column');
		my_register_sidebar('center-column');
		my_register_sidebar('left-center-column');
		my_register_sidebar('left-bottom-column');
		my_register_sidebar('right-column-1');
		my_register_sidebar('right-column-2');
		my_register_sidebar('right-column-3');
		my_register_sidebar('members-column-1');
		my_register_sidebar('members-column-2');
		my_register_sidebar('members-column-3');
		my_register_sidebar('right-column-4');
		my_register_sidebar('right-column-5');
		my_register_sidebar('right-column-6');
		my_register_sidebar('ultimas-blogadas');
	}
	
	// Redirecionar para o profile
	function oci_login_redirect($redirect_to, $set_for, $user)
	{
		$redirect_to = $bp->root_domain . '/' . BP_MEMBERS_SLUG . '/' . $user->user_login . '/';
		
		if($set_for == get_bloginfo('url'))
			return $redirect_to;
		else
			return $set_for;
	}
	
	add_filter('login_redirect', 'oci_login_redirect', 10, 3);
	
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
	
	// Custom comment
	function mytheme_comment($comment, $args, $depth)
	{
		$GLOBALS['comment'] = $comment;
        
        if ( $depth == 1 ) : ?>
        <li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">	
            <div>
                <div class="authorComment">
                    <?php if(get_avatar($comment, $size='80') != '') echo get_avatar($comment, $size='80'); else echo '<img src="'. get_bloginfo('stylesheet_directory') .'/global/graph_content_fotoPerfil.jpg" alt="Sem avatar" width="80" height="80" />'; ?>
                    <p class="autor"><?php print get_comment_author_link() . ' <span>' . get_comment_date( 'j' ) . ' de ' . get_comment_date( 'F' ) . '</span>'; ?></p>
                </div>
            </div>
            <div class="comentario">
                <span class="seta"></span>
				<?php if ($comment->comment_approved == '0') : ?>
                    <p><?php _e('Your comment is awaiting moderation.') ?></p>
                <?php else: ?>
					<?php comment_text() ?>
                <?php endif; ?>
                <?php comment_reply_link(array_merge( $args, array('reply_text' => 'Responder', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            
        <?php else : ?>
        <li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
        	<div class="comment-reply">
				<?php if ($comment->comment_approved == '0') : ?>
                    <p><?php _e('Your comment is awaiting moderation.') ?></p>
                <?php else : ?>
                    <?php comment_text() ?>
                <?php endif; ?>
                <?php comment_reply_link(array_merge( $args, array('reply_text' => 'Responder', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                <div class="autorResposta">
                    <p class="nomeData"><?php print get_comment_author_link() . ' <span>' . get_comment_date( 'j' ) . ' de ' . get_comment_date( 'F' ) . '</span>'; ?></p>
                    <?php if(get_avatar($comment, $size='40') != '') echo get_avatar($comment, $size='40'); else echo '<img src="'. get_bloginfo('stylesheet_directory') .'/global/graph_content_fotoPerfil.jpg" alt="Sem avatar" width="40" height="40" />'; ?>
                </div>
                <div class="clear"></div>
            </div>
        <?php endif;
	}
