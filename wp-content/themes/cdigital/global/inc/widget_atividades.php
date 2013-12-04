<?php
/*
Function Name: Widget Atividades
Plugin URI: http://xemele.cultura.gov.br/
Version: 0.1
Author: Marcos Maia Lopes
Author URI: http://xemele.cultura.gov.br/
*/

class widget_activity extends WP_Widget
{	
	function widget_activity()
	{
		$widget_args = array('classname' => 'widget_activity', 'description' => __( 'Fluxo de atividades em todo site') );
		parent::WP_Widget('activity', __('Fluxo de atividades'), $widget_args);
	}

	function widget($args, $instance)
	{
	    extract($args);
	    $title = apply_filters('widget_title', empty($instance['title']) ? 'Fluxo de atividades' : $instance['title']);
	?>
	    <?php echo $before_widget;
	          echo $before_title . $title . $after_title; ?>

		<?php do_action( 'bp_before_directory_activity_content' ) ?>

		<?php if ( is_user_logged_in() ) : ?>
			<?php locate_template( array( 'activity/post-form.php'), true ) ?>
		<?php endif; ?>

		<?php do_action( 'template_notices' ) ?>

		<div class="item-list-tabs activity-type-tabs">
			<ul class="activity-type-tabs">
				<?php do_action( 'bp_before_activity_type_tab_all' ) ?>

				<li id="activity-all" class="selected"><a href="<?php echo bp_loggedin_user_domain() . BP_ACTIVITY_SLUG . '/' ?>" title="<?php _e( 'The public activity for everyone on this site.', 'buddypress' ) ?>"><?php printf( __( 'Todos os membros', 'buddypress' ), bp_get_total_site_member_count() ) ?></a></li>

				<?php if ( is_user_logged_in() ) : ?>

					<?php do_action( 'bp_before_activity_type_tab_friends' ) ?>

					<?php if ( function_exists( 'bp_get_total_friend_count' ) ) : ?>
						<?php if ( bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>
							<li id="activity-friends">|<a href="<?php echo bp_loggedin_user_domain() . BP_ACTIVITY_SLUG . '/' . BP_FRIENDS_SLUG . '/' ?>" title="<?php _e( 'The activity of my friends only.', 'buddypress' ) ?>"><?php printf( __( 'My Friends', 'buddypress' ), bp_get_total_friend_count( bp_loggedin_user_id() ) ) ?></a></li>
						<?php endif; ?>
					<?php endif; ?>

					<?php do_action( 'bp_before_activity_type_tab_groups' ) ?>

					<?php if ( function_exists( 'bp_get_total_group_count_for_user' ) ) : ?>
						<?php if ( bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>
							<li id="activity-groups">|<a href="<?php echo bp_loggedin_user_domain() . BP_ACTIVITY_SLUG . '/' . BP_GROUPS_SLUG . '/' ?>" title="<?php _e( 'The activity of groups I am a member of.', 'buddypress' ) ?>"><?php printf( __( 'My Groups', 'buddypress' ), bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) ?></a></li>
						<?php endif; ?>
					<?php endif; ?>

					<?php do_action( 'bp_before_activity_type_tab_favorites' ) ?>

					<?php if ( bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ) : ?>
						<li id="activity-favorites">|<a href="<?php echo bp_loggedin_user_domain() . BP_ACTIVITY_SLUG . '/favorites/' ?>" title="<?php _e( "The activity I've marked as a favorite.", 'buddypress' ) ?>"><?php printf( __( 'My Favorites', 'buddypress' ), bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ) ?></a></li>
					<?php endif; ?>

					<?php do_action( 'bp_before_activity_type_tab_mentions' ) ?>

					<li id="activity-mentions">|<a href="<?php echo bp_loggedin_user_domain() . BP_ACTIVITY_SLUG . '/mentions/' ?>" title="<?php _e( 'Activity that I have been mentioned in.', 'buddypress' ) ?>"><?php printf( __( '@%s', 'buddypress' ), bp_get_loggedin_user_username() ) ?><?php if ( bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ) : ?> <strong><?php printf( __( '(%s new)', 'buddypress' ), bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ) ?></strong><?php endif; ?></a></li>

				<?php endif; ?>

				<?php do_action( 'bp_activity_type_tabs' ) ?>
			</ul>
			
		</div>
        
        <a class="feed" href="<?php bp_sitewide_activity_feed_link() ?>" title="RSS Feed"><?php _e( 'RSS', 'buddypress' ) ?></a>

	    <div class="activity">
	        <?php locate_template( array( 'activity/activity-loop.php' ), true ) ?>
	    </div>
	    <?php echo $after_widget;
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		if( $instance != $new_instance )
		{
			$instance = $new_instance;
		}
		
		return $instance;
	}

	function form($instance)
	{
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>">Título:</label></p>
            <p><input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" /></p>
        <?php
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("widget_activity");'));
