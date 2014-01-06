<?php

class Painter_Custom
{
  // ATRIBUTES ////////////////////////////////////////////////////////////////////////////////////
  var $version = 1.0;

  // METHODS //////////////////////////////////////////////////////////////////////////////////////
  	/**
	 * @name    get
	 * @author  Marcelo Mesquita <contato@marcelomesquita.com>
	 * @created 2012-09-25
	 * @updated 2012-09-25
	 * @return  string
	 */
	function get( $parameter )
	{
		if( isset( $_GET[ $parameter ] ) )
			return $_GET[ $parameter ];
		else
			return null;
	}

	/**
	 * @name    post
	 * @author  Marcelo Mesquita <contato@marcelomesquita.com>
	 * @created 2012-09-25
	 * @updated 2012-09-25
	 * @return  string
	 */
	function post( $parameter )
	{
		if( isset( $_POST[ $parameter ] ) )
			return $_POST[ $parameter ];
		else
			return null;
	}

	/**
	 * @name    hexadecimal
	 * @author  Marcelo Mesquita <contato@marcelomesquita.com>
	 * @created 2012-09-29
	 * @updated 2012-09-29
	 * @return  string
	 */
	function hexadecimal( $value )
	{
		if( preg_match( '/\#[a-fA-F0-9]{6}/', $value ) )
			return $value;
		else
			return null;
	}

	/**
	 * @name    align
	 * @author  Marcelo Mesquita <contato@marcelomesquita.com>
	 * @created 2012-09-30
	 * @updated 2012-09-30
	 * @return  string
	 */
	function align( $value )
	{
		if( preg_match( '/(left|right|center)/', $value ) )
			return $value;
		else
			return null;
	}

  /**
	 * initialize theme options
	 *
	 * @name    runonce
	 * @author  Marcelo Mesquita <contato@marcelomesquita.com>
	 * @created 2013-07-23
	 * @updated 2013-10-30
	 * @return  string
	 */
  function runonce()
	{
		// get version
		$painter = get_option( 'painter' );

		// check if is object
		if( !is_object( $painter ) )
			$painter = ( object ) array( 'version' => null, 'options' => null, 'option' => null );

		// check if is updated
		if( $painter->version == $this->version )
			return false;

		// set version
		$painter->version = $this->version;

    // set options
    if( empty( $painter->options ) )
		{
			// title align
			$options[ 'title_align' ]          = 'center';

			// slideshow
			$options[ 'slideshow' ]            = 0;

			// index infos
			$options[ 'index_date' ]           = 1;
			$options[ 'index_modified_date' ]  = 0;
			$options[ 'index_author' ]         = 1;
			$options[ 'index_category' ]       = 0;
			$options[ 'index_tag' ]            = 0;
			$options[ 'index_comments' ]       = 0;

			// post infos
			$options[ 'single_date' ]          = 1;
			$options[ 'single_modified_date' ] = 0;
			$options[ 'single_author' ]        = 1;
			$options[ 'single_category' ]      = 1;
			$options[ 'single_tag' ]           = 1;
			$options[ 'single_comments' ]      = 0;

			$painter->options = $options;
		}

		// set colors
		if( empty( $painter->colors ) )
		{
			// general
			$colors[ 'general_text' ]           = '#000000';
			$colors[ 'general_link' ]           = '#456789';
			$colors[ 'general_link_active' ]    = '#abcdef';
			$colors[ 'general_link_visited' ]   = '#456abc';
			$colors[ 'body_background' ]        = '#ffffff';

			// navigator
			$colors[ 'navigator_text' ]         = '#000000';
			$colors[ 'navigator_link' ]         = '#333333';
			$colors[ 'navigator_link_active' ]  = '#999999';
			$colors[ 'navigator_link_visited' ] = '#666666';

			// content
			$colors[ 'content_title' ]          = '#000000';
			$colors[ 'content_text' ]           = '#333333';
			$colors[ 'content_meta' ]           = '#666666';
			$colors[ 'content_link' ]           = '#456789';
			$colors[ 'content_link_active' ]    = '#abcdef';
			$colors[ 'content_link_visited' ]   = '#789abc';

			// comment
			$colors[ 'comment_title' ]          = '#000000';
			$colors[ 'comment_text' ]           = '#333333';
			$colors[ 'comment_meta' ]           = '#666666';
			$colors[ 'comment_link' ]           = '#456789';
			$colors[ 'comment_link_active' ]    = '#abcdef';
			$colors[ 'comment_link_visited' ]   = '#789abc';

			// widget
			$colors[ 'widget_title' ]           = '#000000';
			$colors[ 'widget_text' ]            = '#333333';
			$colors[ 'widget_meta' ]            = '#666666';
			$colors[ 'widget_link' ]            = '#456789';
			$colors[ 'widget_link_active' ]     = '#abcdef';
			$colors[ 'widget_link_visited' ]    = '#789abc';

			$painter->colors = $colors;
		}

		// update
		update_option( 'painter', $painter );
	}

	 /**
	 * @name    admin_scripts
	 * @author  Marcelo Mesquita <contato@marcelomesquita.com>
	 * @created 2013-07-15
	 * @updated 2013-07-15
	 * @return  string
	 */
  function admin_scripts()
  {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'painter-custom-colors', get_template_directory_uri() . '/js/custom-colors.js', array( 'wp-color-picker' ), false, true );
	}

  /**
	 * add administrative menus
	 *
	 * @name    menus
	 * @author  Marcelo Mesquita <contato@marcelomesquita.com>
	 * @created 2012-03-29
	 * @updated 2013-10-29
	 * @return  string
	 */
  function menus()
  {
    add_theme_page( __( 'Colors', 'painter' ), __( 'Colors', 'painter' ), 'edit_theme_options', 'painter_colors', array( &$this, 'colors' ) );
    add_theme_page( __( 'Options', 'painter' ), __( 'Options', 'painter' ), 'edit_theme_options', 'painter_options', array( &$this, 'options' ) );
  }

  /**
	 * @name    colors
	 * @author  Marcelo Mesquita <contato@marcelomesquita.com>
	 * @created 2013-07-15
	 * @updated 2013-10-30
	 * @return  string
	 */
  function colors()
  {
    if( isset( $_POST[ 'nonce' ] ) )
    	$nonce = $_POST[ 'nonce' ];

    // save colors
    if( !empty( $nonce ) )
		{
			if( wp_verify_nonce( $nonce, 'painter_colors' ) )
			{
				// general
				$colors[ 'general_text' ]           = $this->hexadecimal( $this->post( 'general_text' ) );
				$colors[ 'general_link' ]           = $this->hexadecimal( $this->post( 'general_link' ) );
				$colors[ 'general_link_active' ]    = $this->hexadecimal( $this->post( 'general_link_active' ) );
				$colors[ 'general_link_visited' ]   = $this->hexadecimal( $this->post( 'general_link_visited' ) );
				$colors[ 'body_background' ]        = $this->hexadecimal( $this->post( 'body_background' ) );

				// navigator
				$colors[ 'navigator_text' ]         = $this->hexadecimal( $this->post( 'navigator_text' ) );
				$colors[ 'navigator_link' ]         = $this->hexadecimal( $this->post( 'navigator_link' ) );
				$colors[ 'navigator_link_active' ]  = $this->hexadecimal( $this->post( 'navigator_link_active' ) );
				$colors[ 'navigator_link_visited' ] = $this->hexadecimal( $this->post( 'navigator_link_visited' ) );

				// content
				$colors[ 'content_title' ]          = $this->hexadecimal( $this->post( 'content_title' ) );
				$colors[ 'content_text' ]           = $this->hexadecimal( $this->post( 'content_text' ) );
				$colors[ 'content_meta' ]           = $this->hexadecimal( $this->post( 'content_meta' ) );
				$colors[ 'content_link' ]           = $this->hexadecimal( $this->post( 'content_link' ) );
				$colors[ 'content_link_active' ]    = $this->hexadecimal( $this->post( 'content_link_active' ) );
				$colors[ 'content_link_visited' ]   = $this->hexadecimal( $this->post( 'content_link_visited' ) );

				// comment
				$colors[ 'comment_title' ]          = $this->hexadecimal( $this->post( 'comment_title' ) );
				$colors[ 'comment_text' ]           = $this->hexadecimal( $this->post( 'comment_text' ) );
				$colors[ 'comment_meta' ]           = $this->hexadecimal( $this->post( 'comment_meta' ) );
				$colors[ 'comment_link' ]           = $this->hexadecimal( $this->post( 'comment_link' ) );
				$colors[ 'comment_link_active' ]    = $this->hexadecimal( $this->post( 'comment_link_active' ) );
				$colors[ 'comment_link_visited' ]   = $this->hexadecimal( $this->post( 'comment_link_visited' ) );

				// widget
				$colors[ 'widget_title' ]           = $this->hexadecimal( $this->post( 'widget_title' ) );
				$colors[ 'widget_text' ]            = $this->hexadecimal( $this->post( 'widget_text' ) );
				$colors[ 'widget_meta' ]            = $this->hexadecimal( $this->post( 'widget_meta' ) );
				$colors[ 'widget_link' ]            = $this->hexadecimal( $this->post( 'widget_link' ) );
				$colors[ 'widget_link_active' ]     = $this->hexadecimal( $this->post( 'widget_link_active' ) );
				$colors[ 'widget_link_visited' ]    = $this->hexadecimal( $this->post( 'widget_link_visited' ) );

				// get colors
				$painter = get_option( 'painter' );

				$painter->colors = $colors;

				// update colors
				update_option( 'painter', $painter );

				// show message
				echo '<div class="updated"><p><strong>' . __( 'Updated!', 'painter' ) . '</strong></p></div>';
			}
		}

		// get colors
		$painter = get_option( 'painter' );

    $colors = $painter->colors;

    // form
    ?>
      <div class="wrap">
        <h2><?php _e( 'Custom Colors', 'painter' ); ?></h2>
        <form method="post">
					<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'painter_colors' ); ?>" />

          <h3><?php _e( 'General', 'painter' ); ?></h3>
          <table class="form-table">
            <tbody>
							<tr valign="top">
                <th scope="row">
									<label for="general_text"><strong><?php _e( 'Text', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="general_text" name="general_text" value="<?php echo $colors[ 'general_text' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="general_link"><strong><?php _e( 'Link', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="general_link" name="general_link" value="<?php echo $colors[ 'general_link' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="general_link_active"><strong><?php _e( 'Link Active', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="general_link_active" name="general_link_active" value="<?php echo $colors[ 'general_link_active' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="general_link_visited"><strong><?php _e( 'Link Visited', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="general_link_visited" name="general_link_visited" value="<?php echo $colors[ 'general_link_visited' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="body_background"><strong><?php _e( 'Container Background', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="body_background" name="body_background" value="<?php echo $colors[ 'body_background' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
            </tbody>
          </table>
          <p class="submit">
            <button type="submit" class="button-primary"><?php _e( 'Save', 'painter' ); ?></button>
          </p>

          <h3><?php _e( 'Navigator', 'painter' ); ?></h3>
          <table class="form-table">
            <tbody>
							<tr valign="top">
                <th scope="row">
									<label for="navigator_text"><strong><?php _e( 'Text', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="navigator_text" name="navigator_text" value="<?php echo $colors[ 'navigator_text' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="navigator_link"><strong><?php _e( 'Link', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="navigator_link" name="navigator_link" value="<?php echo $colors[ 'navigator_link' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="navigator_link_active"><strong><?php _e( 'Link Active', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="navigator_link_active" name="navigator_link_active" value="<?php echo $colors[ 'navigator_link_active' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="navigator_link_visited"><strong><?php _e( 'Link Visited', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="navigator_link_visited" name="navigator_link_visited" value="<?php echo $colors[ 'navigator_link_visited' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
            </tbody>
          </table>
          <p class="submit">
            <button type="submit" class="button-primary"><?php _e( 'Save', 'painter' ); ?></button>
          </p>

          <h3><?php _e( 'Content', 'painter' ); ?></h3>
          <table class="form-table">
            <tbody>
              <tr valign="top">
                <th scope="row">
									<label for="content_title"><strong><?php _e( 'Title', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="content_title" name="content_title" value="<?php echo $colors[ 'content_title' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="content_text"><strong><?php _e( 'Text', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="content_text" name="content_text" value="<?php echo $colors[ 'content_text' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="content_meta"><strong><?php _e( 'Meta', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="content_meta" name="content_meta" value="<?php echo $colors[ 'content_meta' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="content_link"><strong><?php _e( 'Link', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="content_link" name="content_link" value="<?php echo $colors[ 'content_link' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="content_link_active"><strong><?php _e( 'Link Active', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="content_link_active" name="content_link_active" value="<?php echo $colors[ 'content_link_active' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="content_link_visited"><strong><?php _e( 'Link Visited', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="content_link_visited" name="content_link_visited" value="<?php echo $colors[ 'content_link_visited' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
            </tbody>
          </table>
          <p class="submit">
            <button type="submit" class="button-primary"><?php _e( 'Save', 'painter' ); ?></button>
          </p>

          <h3><?php _e( 'Comment', 'painter' ); ?></h3>
          <table class="form-table">
            <tbody>
              <tr valign="top">
                <th scope="row">
									<label for="comment_title"><strong><?php _e( 'Title', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="comment_title" name="comment_title" value="<?php echo $colors[ 'comment_title' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="comment_text"><strong><?php _e( 'Text', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="comment_text" name="comment_text" value="<?php echo $colors[ 'comment_text' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="comment_meta"><strong><?php _e( 'Meta', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="comment_meta" name="comment_meta" value="<?php echo $colors[ 'comment_meta' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="comment_link"><strong><?php _e( 'Link', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="comment_link" name="comment_link" value="<?php echo $colors[ 'comment_link' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="comment_link_active"><strong><?php _e( 'Link Active', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="comment_link_active" name="comment_link_active" value="<?php echo $colors[ 'comment_link_active' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="comment_link_visited"><strong><?php _e( 'Link Visited', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="comment_link_visited" name="comment_link_visited" value="<?php echo $colors[ 'comment_link_visited' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
            </tbody>
          </table>
          <p class="submit">
            <button type="submit" class="button-primary"><?php _e( 'Save', 'painter' ); ?></button>
          </p>

          <h3><?php _e( 'Widgets', 'painter' ); ?></h3>
          <table class="form-table">
            <tbody>
              <tr valign="top">
                <th scope="row">
									<label for="widget_title"><strong><?php _e( 'Title', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="widget_title" name="widget_title" value="<?php echo $colors[ 'widget_title' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="widget_text"><strong><?php _e( 'Text', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="widget_text" name="widget_text" value="<?php echo $colors[ 'widget_text' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="widget_meta"><strong><?php _e( 'Meta', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="widget_meta" name="widget_meta" value="<?php echo $colors[ 'widget_meta' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="widget_link"><strong><?php _e( 'Link', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="widget_link" name="widget_link" value="<?php echo $colors[ 'widget_link' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="widget_link_active"><strong><?php _e( 'Link Active', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="widget_link_active" name="widget_link_active" value="<?php echo $colors[ 'widget_link_active' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
									<label for="widget_link_visited"><strong><?php _e( 'Link Visited', 'painter' ); ?></strong></label>
								</th>
                <td>
                  <input type="text" id="widget_link_visited" name="widget_link_visited" value="<?php echo $colors[ 'widget_link_visited' ]; ?>" maxlength="7" class="color_picker" />
                </td>
              </tr>
            </tbody>
          </table>
          <p class="submit">
            <button type="submit" class="button-primary"><?php _e( 'Save', 'painter' ); ?></button>
          </p>
        </form>
      </div>
    <?php
  }

  /**
	 * @name    options
	 * @author  Marcelo Mesquita <contato@marcelomesquita.com>
	 * @created 2012-03-29
	 * @updated 2013-10-30
	 * @return  string
	 */
  function options()
  {
    if( isset( $_POST[ 'nonce' ] ) )
    	$nonce = $_POST[ 'nonce' ];

    // save options
    if( !empty( $nonce ) )
		{
			if( wp_verify_nonce( $nonce, 'painter_options' ) )
			{
				// title align
				$options[ 'title_align' ]          = $this->align( $this->post( 'title_align' ) );

				// slideshow
				$options[ 'slideshow' ]            = ( int ) $this->post( 'slideshow' );

				// index infos
				$options[ 'index_date' ]           = ( bool ) $this->post( 'index_date' );
				$options[ 'index_modified_date' ]  = ( bool ) $this->post( 'index_modified_date' );
				$options[ 'index_author' ]         = ( bool ) $this->post( 'index_author' );
				$options[ 'index_category' ]       = ( bool ) $this->post( 'index_category' );
				$options[ 'index_tag' ]            = ( bool ) $this->post( 'index_tag' );
				$options[ 'index_comments' ]       = ( bool ) $this->post( 'index_comments' );

				// post infos
				$options[ 'single_date' ]          = ( bool ) $this->post( 'single_date' );
				$options[ 'single_modified_date' ] = ( bool ) $this->post( 'single_modified_date' );
				$options[ 'single_author' ]        = ( bool ) $this->post( 'single_author' );
				$options[ 'single_category' ]      = ( bool ) $this->post( 'single_category' );
				$options[ 'single_tag' ]           = ( bool ) $this->post( 'single_tag' );
				$options[ 'single_comments' ]      = ( bool ) $this->post( 'single_comments' );

				// get options
				$painter = get_option( 'painter' );

				$painter->options = $options;

				// update options
				update_option( 'painter', $painter );

				// show message
				echo '<div class="updated"><p><strong>' . __( 'Updated!', 'painter' ) . '</strong></p></div>';
			}
		}

    // get options
    $painter = get_option( 'painter' );

    $options = $painter->options;

    // form
    ?>
      <div class="wrap">
        <h2><?php _e( 'Custom Options', 'painter' ); ?></h2>
        <form method="post" action="">
					<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'painter_options' ); ?>" />
          <table class="form-table">
            <tbody>
              <tr valign="top">
                <th scope="row">
									<label for="title_align"><strong><?php _e( 'Title Alignment', 'painter' ); ?></strong></label>
								</th>
                <td>
                	<select id="title_align" name="title_align">
                		<option value="left" <?php selected( $options[ 'title_align' ], 'left' ); ?>><?php _e( 'left', 'painter' ); ?></option>
                		<option value="right" <?php selected( $options[ 'title_align' ], 'right' ); ?>><?php _e( 'right', 'painter' ); ?></option>
                		<option value="center" <?php selected( $options[ 'title_align' ], 'center' ); ?>><?php _e( 'center', 'painter' ); ?></option>
                	</select>
                </td>
              </tr>

							<tr valign="top">
                <th scope="row">
									<label for="slideshow"><strong><?php _e( 'Slideshow', 'painter' ); ?></strong></label>
									<p class="description"><?php _e( 'publish posts in selected category to appear on slideshow', 'painter' ); ?></p>
								</th>
                <td>
                  <?php wp_dropdown_categories( "name=slideshow&show_option_none=" . __( 'none', 'painter' ) . "&hierarchical=1&hide_empty=0&selected={$options[ 'slideshow' ]}" ); ?>
                </td>
              </tr>

              <tr valign="top">
                <th scope="row">
									<strong><?php _e( 'Index Informations', 'painter' ); ?></strong>
									<p class="description"><?php _e( 'choose the informations to be displayed on index templates (home, index, category, tags, date, author, taxonomies)', 'painter' ); ?></p>
								</th>
                <td>
									<label><input type="checkbox" name="index_date" value="1" <?php checked( $options[ 'index_date' ], 1 ); ?> /> <?php _e( 'date', 'painter' ); ?></label><br>
									<label><input type="checkbox" name="index_modified_date" value="1" <?php checked( $options[ 'index_modified_date' ], 1 ); ?> /> <?php _e( 'updated date', 'painter' ); ?></label><br>
                  <label><input type="checkbox" name="index_author" value="1" <?php checked( $options[ 'index_author' ], 1 ); ?> /> <?php _e( 'author', 'painter' ); ?></label><br>
                  <label><input type="checkbox" name="index_category" value="1" <?php checked( $options[ 'index_category' ], 1 ); ?> /> <?php _e( 'categories', 'painter' ); ?></label><br>
                  <label><input type="checkbox" name="index_tag" value="1" <?php checked( $options[ 'index_tag' ], 1 ); ?> /> <?php _e( 'tags', 'painter' ); ?></label><br>
                  <label><input type="checkbox" name="index_comments" value="1" <?php checked( $options[ 'index_comments' ], 1 ); ?> /> <?php _e( 'comments', 'painter' ); ?></label><br>
                </td>
              </tr>

							<tr valign="top">
                <th scope="row">
									<strong><?php _e( 'Single Informations', 'painter' ); ?></strong>
									<p class="description"><?php _e( 'choose the informations to be displayed on single templates (single, page, post types)', 'painter' ); ?></p>
								</th>
                <td>
									<label><input type="checkbox" name="single_date" value="1" <?php checked( $options[ 'single_date' ], 1 ); ?> /> <?php _e( 'date', 'painter' ); ?></label><br>
									<label><input type="checkbox" name="single_modified_date" value="1" <?php checked( $options[ 'single_modified_date' ], 1 ); ?> /> <?php _e( 'updated date', 'painter' ); ?></label><br>
                  <label><input type="checkbox" name="single_author" value="1" <?php checked( $options[ 'single_author' ], 1 ); ?> /> <?php _e( 'author', 'painter' ); ?></label><br>
                  <label><input type="checkbox" name="single_category" value="1" <?php checked( $options[ 'single_category' ], 1 ); ?> /> <?php _e( 'categories', 'painter' ); ?></label><br>
                  <label><input type="checkbox" name="single_tag" value="1" <?php checked( $options[ 'single_tag' ], 1 ); ?> /> <?php _e( 'tags', 'painter' ); ?></label><br>
                  <label><input type="checkbox" name="single_comments" value="1" <?php checked( $options[ 'single_comments' ], 1 ); ?> /> <?php _e( 'comments', 'painter' ); ?></label><br>
                </td>
              </tr>
            </tbody>
          </table>
          <p class="submit">
            <button type="submit" class="button-primary"><?php _e( 'Save', 'painter' ); ?></button>
          </p>
        </form>
      </div>
    <?php
  }

  /**
	 * @name    admin_header_image
	 * @author  Marcelo Mesquita <contato@marcelomesquita.com>
	 * @created 2013-07-30
	 * @updated 2013-07-30
	 * @return  string
	 */
	function admin_header_image()
	{
		?>
			<div id="headimg">
				<h1><a id="name" href="#"><?php bloginfo( 'name' ); ?></a></h1>
				<h2><?php bloginfo( 'description' ); ?></h2>
			</div>
		<?php
	}

	/**
	 * @name    admin_header_style
	 * @author  Marcelo Mesquita <contato@marcelomesquita.com>
	 * @created 2013-07-30
	 * @updated 2013-08-08
	 * @return  string
	 */
	function admin_header_style()
	{
		$header_image = get_header_image();

		?>
			<style type="text/css" media="screen">
				#headimg {
					width: <?php echo get_custom_header()->width; ?>px;
					height: <?php echo get_custom_header()->height; ?>px;
					background-color: #<?php echo get_background_color(); ?>;
					<?php if( !empty( $header_image ) ) : ?>
						background-image: url( <?php header_image(); ?> );
						background-repeat: no-repeat;
						background-position: center;
					<?php endif; ?>
				}

				<?php if( 'blank' == get_header_textcolor() or '' == get_header_textcolor() ) : ?>
					#headimg h1,
					#headimg h2 {
						display: none;
					}
				<?php else : ?>
					#headimg a {
						color: #<?php echo get_header_textcolor(); ?>;
						text-decoration: none;
					}

					#headimg h1 {
						font-size: 3.0em;
						font-weight: bold;
						text-align: center;
						margin: 0px 0px 0px 0px;
						padding: 100px 0px 10px 0px;
						text-shadow: 1px 1px 1px rgb( 255, 255, 255 );
					}

					#headimg h2
					{
						font-size: 1.2em;
						font-weight: normal;
						text-align: center;
						margin: 0px 0px 0px 0px;
						padding: 10px 0px 0px 0px;
						text-shadow: 1px 1px 1px rgb( 255, 255, 255 );
					}
				<?php endif; ?>
			</style>
		<?php
	}

  // CONSTRUCTOR //////////////////////////////////////////////////////////////////////////////////
  /**
	 * @name    Painter_Custom
	 * @author  Marcelo Mesquita <contato@marcelomesquita.com>
	 * @created 2012-03-29
	 * @updated 2013-10-19
	 * @return  string
	 */
  function Painter_Custom()
  {
    // custom background
		add_theme_support( 'custom-background' );

    // custom header
		$header_defaults = array(
			'default-image'          => get_template_directory_uri() . '/img/temp/head.png',
			'random-default'         => false,
			'width'                  => 960,
			'height'                 => 200,
			'flex-height'            => false,
			'flex-width'             => false,
			'default-text-color'     => 'ffffff',
			'header-text'            => true,
			'uploads'                => true,
			'wp-head-callback'       => '',
			'admin-head-callback'    => array( &$this, 'admin_header_style' ),
			'admin-preview-callback' => array( &$this, 'admin_header_image' ),
		);

		add_theme_support( 'custom-header', $header_defaults );

    // ativar o menu
    add_action( 'admin_menu', array( &$this, 'menus' ) );

    // carregar os scripts
    add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ) );

    $this->runonce();
  }

  // DESTRUCTOR ///////////////////////////////////////////////////////////////////////////////////

}

$Painter_Custom = new Painter_Custom();

?>