<?php

add_action( 'init', array( 'MotionOptions', 'init' ) );

class MotionOptions {

	function init() {
		add_action( 'admin_menu', array( 'MotionOptions', 'add_options_page' ) );
	}

	function add_options_page() {
  		add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', basename(__FILE__), array( 'MotionOptions', 'page' ) );
	}

	function page() {

		register_setting( 'motionops', 'motion_hide_categories' );
		register_setting( 'motionops', 'motion_hide_homelink' );

		$motion_hide_categories = get_option( 'motion_hide_categories' );
		$motion_hide_homelink = get_option( 'motion_hide_homelink' );

		if ( isset( $_POST['action'] ) && 'update' == esc_attr( $_POST['action'] ) ) {

			if ( !isset( $_POST[ 'motion_hide_categories' ] ) ) {
				$motion_hide_categories = false;
			} else {
				$motion_hide_categories = $_POST[ 'motion_hide_categories' ];
			}

			if ( !isset( $_POST[ 'motion_hide_homelink' ] ) ) {
				$motion_hide_homelink = false;
			} else {
				$motion_hide_homelink = $_POST[ 'motion_hide_homelink' ];
			}

			update_option( 'motion_hide_categories', $motion_hide_categories );
			update_option( 'motion_hide_homelink', $motion_hide_homelink );

		?>
			<div class="updated"><p><strong><?php _e( 'Options saved.' ); ?></strong></p></div>
		<?php

    	} ?>

		<div class="wrap">
	    <?php echo "<h2>" . __( 'Motion Theme Options' ) . "</h2>"; ?>

		<form name="form1" method="post" action="<?php echo esc_attr( str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ) ); ?>">
			<fieldset>
				<?php settings_fields( 'motionops' ); ?>

				<h3><?php _e( 'Category Navigation' ) ?></h3>
				<p>
					<input id="motion_hide_categories" type="checkbox" name="motion_hide_categories" <?php if ( $motion_hide_categories == 1 ) echo 'checked="checked"'; ?> value="1" />
					<label for="motion_hide_categories"><?php _e( 'Hide category links in top navigation' ); ?></label>
				</p>

				<h3><?php _e( 'Home Link' ) ?></h3>
				<p>
					<input id="motion_hide_homelink" type="checkbox" name="motion_hide_homelink" <?php if ( $motion_hide_homelink == 1 ) echo 'checked="checked"'; ?> value="1" />
					<label for="motion_hide_homelink"><?php _e( 'Hide "Home" link in page navigation' ); ?></label>
				</p>

				<p class="submit">
					<input type="submit" name="Submit" value="<?php esc_attr_e( 'Update Options' ) ?>" />
				</p>
			</fieldset>
		</form>
		</div>

<?php
	}
}