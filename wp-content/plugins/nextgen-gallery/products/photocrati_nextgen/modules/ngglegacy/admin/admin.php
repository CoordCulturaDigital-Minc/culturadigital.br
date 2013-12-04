<?php
/**
 * nggAdminPanel - Admin Section for NextGEN Gallery
 *
 * @package NextGEN Gallery
 * @author Alex Rabe
 *
 * @since 1.0.0
 */
class nggAdminPanel{

	// constructor
	function __construct() {

		// Buffer the output
		add_action('admin_init', array(&$this, 'start_buffer'));

		// Add the admin menu
		add_action( 'admin_menu', array (&$this, 'add_menu') );
        add_action( 'admin_bar_menu', array(&$this, 'admin_bar_menu'), 99 );
		add_action( 'network_admin_menu', array (&$this, 'add_network_admin_menu') );

		// Add the script and style files
		add_action('admin_print_scripts', array(&$this, 'load_scripts') );
		add_action('admin_print_styles', array(&$this, 'load_styles') );

		// Try to detect plugins that embed their own jQuery and jQuery UI
		// libraries and load them in NGG's admin pages
		add_action('admin_enqueue_scripts', array(&$this, 'buffer_scripts'), 0);
		add_action('admin_print_scripts', array(&$this, 'output_scripts'), PHP_INT_MAX);

        //TODO: remove after release of Wordpress 3.3
		add_filter('contextual_help', array(&$this, 'show_help'), 10, 2);
        add_filter('current_screen', array(&$this, 'edit_current_screen'));

        // Add WPML hook to register description / alt text for translation
        add_action('ngg_image_updated', array('nggGallery', 'RegisterString') );

	}

	function start_buffer()
	{
		// Notify of page event
		if (isset($_REQUEST['page']) && $_POST) {
			$event = array(
				'event'	=>	str_replace('-', '_', str_replace('nggallery', '', $_REQUEST['page']))
			);

			// Do we have a list of galleries that are being affected?
			if (isset($_REQUEST['doaction'])) {
				$event['gallery_ids']	= $_REQUEST['doaction'];
			}

			// Do we have a particular gallery id?
			elseif (isset($_REQUEST['gid'])) {
				$event['gallery_id']	= $_REQUEST['gid'];
			}

			// Do we have an album id?
			elseif (isset($_REQUEST['act_album']) && $_REQUEST['act_album']) {
				$event['album_id']		= $_REQUEST['act_album'];
			}
			if (strpos($event['event'], '_') === 0) $event['event'] = substr($event['event'], 1);

			do_action('ngg_page_event', $event);
		}
		ob_start();
	}

	/**
	 * If a NGG page is being requested, we buffer any rendering of <script>
	 * tags to detect conflicts and remove them if need be
	 */
	function buffer_scripts()
	{
		// Is this a NGG admin page?
		if (isset($_REQUEST['page']) && strpos($_REQUEST['page'] ,'nggallery') !== FALSE) {
			ob_start();
		}
	}

	function output_scripts()
	{
		// Is this a NGG admin page?
		if (isset($_REQUEST['page']) && strpos($_REQUEST['page'] ,'nggallery') !== FALSE) {
			$plugin_folder		= NGGFOLDER;
			$skipjs_count		= 0;
			$html = ob_get_contents();
			ob_end_clean();

            if (!defined('NGG_JQUERY_CONFLICT_DETECTION')) {
				define('NGG_JQUERY_CONFLICT_DETECTION', TRUE);
			}

			if (NGG_JQUERY_CONFLICT_DETECTION) {
				// Detect custom jQuery script
				if (preg_match_all("/<script.*wp-content.*jquery[-_\.](min\.)?js.*<\script>/", $html, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$old_script = array_shift($match);
						if (strpos($old_script, NEXTGEN_GALLERY_PLUGIN_DIR) === FALSE)
							$html = str_replace($old_script, '', $html);
					}
				}

				// Detect custom jQuery UI script and remove
				if (preg_match_all("/<script.*wp-content.*jquery[-_\.]ui.*<\/script>/", $html, $matches, PREG_SET_ORDER)) {
					$detected_jquery_ui = TRUE;
					foreach ($matches as $match) {
						$old_script = array_shift($match);
						if (strpos($old_script, NEXTGEN_GALLERY_PLUGIN_DIR) === FALSE)
							$html = str_replace($old_script, '', $html);
					}
				}

				if (isset($_REQUEST['skipjs'])) {
					foreach ($_REQUEST['skipjs'] as $js) {
						$js = preg_quote($js);
						if (preg_match_all("#<script.*{$js}.*</script>#", $html, $matches, PREG_SET_ORDER)) {
							foreach ($matches as $match) {
								$old_script = array_shift($match);
								if (strpos($old_script, NGGFOLDER) === FALSE)
									$html = str_replace($old_script, '', $html);
							}
						}
					}
					$skipjs_count = count($_REQUEST['skipjs']);
				}


				// Use WordPress built-in version of jQuery
				$jquery_url = includes_url('js/jquery/jquery.js');
				$html = implode('', array(
					"<script type='text/javascript' src='{$jquery_url}'></script>\n",
					"<script type='text/javascript'>
					window.onerror = function(msg, url, line){
						if (url.match(/\.js$|\.js\?/)) {
							if (window.location.search.length > 0) {
								if (window.location.search.indexOf(url) == -1)
									window.location.search += '&skipjs[{$skipjs_count}]='+url;
							}
							else {
								window.location.search = '?skipjs[{$skipjs_count}]='+url;
							}
						}
						return true;
					};</script>\n",
					$html
				));
			}

			echo $html;
		}
	}

	// integrate the menu
	function add_menu()  {

		add_menu_page( _n( 'Gallery', 'Galleries', 1, 'nggallery' ), _n( 'Gallery', 'Galleries', 1, 'nggallery' ), 'NextGEN Gallery overview', NGGFOLDER, array (&$this, 'show_menu'), path_join(NGGALLERY_URLPATH, 'admin/images/nextgen_16_color.png') );
	    add_submenu_page( NGGFOLDER , __('Overview', 'nggallery'), __('Overview', 'nggallery'), 'NextGEN Gallery overview', NGGFOLDER, array (&$this, 'show_menu'));
	    add_submenu_page( NGGFOLDER , __('Manage Galleries', 'nggallery'), __('Manage Galleries', 'nggallery'), 'NextGEN Manage gallery', 'nggallery-manage-gallery', array (&$this, 'show_menu'));
	    add_submenu_page( NGGFOLDER , _n( 'Manage Albums', 'Albums', 1, 'nggallery' ), _n( 'Manage Albums', 'Manage Albums', 1, 'nggallery' ), 'NextGEN Edit album', 'nggallery-manage-album', array (&$this, 'show_menu'));
	    add_submenu_page( NGGFOLDER , __('Manage Tags', 'nggallery'), __('Manage Tags', 'nggallery'), 'NextGEN Manage tags', 'nggallery-tags', array (&$this, 'show_menu'));
//	    if ( !is_multisite() || wpmu_site_admin() )
//            add_submenu_page( NGGFOLDER , __('Reset / Uninstall', 'nggallery'), __('Reset / Uninstall', 'nggallery'), 'activate_plugins', 'nggallery-setup', array (&$this, 'show_menu'));

		//register the column fields
		$this->register_columns();
	}

	// integrate the network menu
	function add_network_admin_menu()  {

		add_menu_page( _n( 'Gallery', 'Galleries', 1, 'nggallery' ), _n( 'Gallery', 'Galleries', 1, 'nggallery' ), 'nggallery-wpmu', NGGFOLDER, array (&$this, 'show_network_settings'), path_join(NGGALLERY_URLPATH, 'admin/images/nextgen_16_color.png') );
		add_submenu_page( NGGFOLDER , __('Network settings', 'nggallery'), __('Network settings', 'nggallery'), 'nggallery-wpmu', NGGFOLDER,  array (&$this, 'show_network_settings'));
        // add_submenu_page( NGGFOLDER , __('Reset / Uninstall', 'nggallery'), __('Reset / Uninstall', 'nggallery'), 'activate_plugins', 'nggallery-setup', array (&$this, 'show_menu'));
	}

    /**
     * Adding NextGEN Gallery to the Admin bar
     *
     * @since 1.9.0
     *
     * @return void
     */
    function admin_bar_menu() {
    	// If the current user can't write posts, this is all of no use, so let's not output an admin menu
    	if ( !current_user_can('NextGEN Gallery overview') )
    		return;

    	global $wp_admin_bar;

    	$wp_admin_bar->add_menu( array( 'id' => 'ngg-menu', 'title' => __( 'Gallery' ), 'href' => admin_url('admin.php?page='. NGGFOLDER) ) );
        $wp_admin_bar->add_menu( array( 'parent' => 'ngg-menu', 'id' => 'ngg-menu-overview', 'title' => __('Overview', 'nggallery'), 'href' => admin_url('admin.php?page='. NGGFOLDER) ) );
        if ( current_user_can('NextGEN Upload images') )
            $wp_admin_bar->add_menu( array( 'parent' => 'ngg-menu', 'id' => 'ngg-menu-add-gallery', 'title' => __('Add Gallery / Images', 'nggallery'), 'href' => admin_url('admin.php?page=ngg_addgallery') ) );
        if ( current_user_can('NextGEN Manage gallery') )
            $wp_admin_bar->add_menu( array( 'parent' => 'ngg-menu', 'id' => 'ngg-menu-manage-gallery', 'title' => __('Manage Galleries', 'nggallery'), 'href' => admin_url('admin.php?page=nggallery-manage-gallery') ) );
        if ( current_user_can('NextGEN Edit album') )
            $wp_admin_bar->add_menu( array( 'parent' => 'ngg-menu', 'id' => 'ngg-menu-manage-album', 'title' => _n( 'Manage Albums', 'Manage Albums', 1, 'nggallery' ), 'href' => admin_url('admin.php?page=nggallery-manage-album') ) );
        if ( current_user_can('NextGEN Manage tags') )
            $wp_admin_bar->add_menu( array( 'parent' => 'ngg-menu', 'id' => 'ngg-menu-tags', 'title' => __('Manage Tags', 'nggallery'), 'href' => admin_url('admin.php?page=nggallery-tags') ) );
    }

    // show the network page
    function show_network_settings() {
		include_once ( dirname (__FILE__) . '/style.php' );
		include_once ( dirname (__FILE__) . '/wpmu.php' );
		nggallery_wpmu_setup();
    }

	// load the script for the defined page and load only this code
	function show_menu() {
		global $ngg;

		// Set installation date
		if( empty($ngg->options['installDate']) ) {
			$ngg->options['installDate'] = time();
			update_option('ngg_options', $ngg->options);
		}

		// Show donation message only one time.
		if (isset ( $_GET['hide_donation']) ) {
			$ngg->options['hideDonation'] = true;
			update_option('ngg_options', $ngg->options);
		}

		echo '<div id="ngg_page_content">';
  		switch ($_GET['page']){
			case "nggallery-manage-gallery" :
				include_once ( dirname (__FILE__) . '/functions.php' );	// admin functions
				include_once ( dirname (__FILE__) . '/manage.php' );	// nggallery_admin_manage_gallery
				// Initate the Manage Gallery page
				$ngg->manage_page = new nggManageGallery ();
				// Render the output now, because you cannot access a object during the constructor is not finished
				$ngg->manage_page->controller();
				break;
			case "nggallery-manage-album" :
				include_once ( dirname (__FILE__) . '/album.php' );		// nggallery_admin_manage_album
				$ngg->manage_album = new nggManageAlbum ();
				$ngg->manage_album->controller();
				break;
			case "nggallery-options" :
				include_once ( dirname (__FILE__) . '/settings.php' );	// nggallery_admin_options
				$ngg->option_page = new nggOptions ();
				$ngg->option_page->controller();
				break;
			case "nggallery-tags" :
				include_once ( dirname (__FILE__) . '/tags.php' );		// nggallery_admin_tags
				break;
			case "nggallery-style" :
				include_once ( dirname (__FILE__) . '/style.php' );		// nggallery_admin_style
				nggallery_admin_style();
				break;
			case "nggallery-setup" :
				include_once ( dirname (__FILE__) . '/setup.php' );		// nggallery_admin_setup
				nggallery_admin_setup();
				break;
			case "nggallery-roles" :
				include_once ( dirname (__FILE__) . '/roles.php' );		// nggallery_admin_roles
				nggallery_admin_roles();
				break;
			case "nggallery-import" :
				include_once ( dirname (__FILE__) . '/myimport.php' );	// nggallery_admin_import
				nggallery_admin_import();
				break;
			case "nggallery-about" :
				include_once ( dirname (__FILE__) . '/about.php' );		// nggallery_admin_about
				nggallery_admin_about();
				break;
			case "nggallery" :
			default :
				include_once ( dirname (__FILE__) . '/overview.php' ); 	// nggallery_admin_overview
				nggallery_admin_overview();
				break;
		}
		echo "</div>";
	}

	function load_scripts() {
		global $wp_version;

		// no need to go on if it's not a plugin page
		if( !isset($_GET['page']) )
			return;

		// If we're on a NextGen Page
//		if (preg_match("/ngg|nextgen-gallery/", $_GET['page'])) {
//			wp_register_script('ngg_social_media', path_join(
//				NGGALLERY_URLPATH,
//				'admin/js/ngg_social_media.js'
//			), array('jquery'));
//
//			wp_register_style('ngg_social_media', path_join(
//				NGGALLERY_URLPATH,
//				'admin/css/ngg_social_media.css'
//			));
//
//			wp_enqueue_style('ngg_social_media');
//			wp_enqueue_script('ngg_social_media');
//		}

        // used to retrieve the uri of some module resources
        $router = C_Component_Registry::get_instance()->get_utility('I_Router');

		wp_register_script('ngg-ajax', NGGALLERY_URLPATH . 'admin/js/ngg.ajax.js', array('jquery'), '1.4.1');
		wp_localize_script('ngg-ajax', 'nggAjaxSetup', array(
					'url' => admin_url('admin-ajax.php'),
					'action' => 'ngg_ajax_operation',
					'operation' => '',
					'nonce' => wp_create_nonce( 'ngg-ajax' ),
					'ids' => '',
					'permission' => __('You do not have the correct permission', 'nggallery'),
					'error' => __('Unexpected Error', 'nggallery'),
					'failure' => __('A failure occurred', 'nggallery')
		) );
        wp_register_script( 'ngg-plupload-handler', NGGALLERY_URLPATH .'admin/js/plupload.handler.js', array('plupload-all'), '0.0.1' );
    	wp_localize_script( 'ngg-plupload-handler', 'pluploadL10n', array(
    		'queue_limit_exceeded' => __('You have attempted to queue too many files.'),
    		'file_exceeds_size_limit' => __('This file exceeds the maximum upload size for this site.'),
    		'zero_byte_file' => __('This file is empty. Please try another.'),
    		'invalid_filetype' => __('This file type is not allowed. Please try another.'),
    		'not_an_image' => __('This file is not an image. Please try another.'),
    		'image_memory_exceeded' => __('Memory exceeded. Please try another smaller file.'),
    		'image_dimensions_exceeded' => __('This is larger than the maximum size. Please try another.'),
    		'default_error' => __('An error occurred in the upload. Please try again later.'),
    		'missing_upload_url' => __('There was a configuration error. Please contact the server administrator.'),
    		'upload_limit_exceeded' => __('You may only upload 1 file.'),
    		'http_error' => __('HTTP error.'),
    		'upload_failed' => __('Upload failed.'),
    		'io_error' => __('IO error.'),
    		'security_error' => __('Security error.'),
    		'file_cancelled' => __('File canceled.'),
    		'upload_stopped' => __('Upload stopped.'),
    		'dismiss' => __('Dismiss'),
    		'crunching' => __('Crunching&hellip;'),
    		'deleted' => __('moved to the trash.'),
    		'error_uploading' => __('&#8220;%s&#8221; has failed to upload due to an error')
    	) );
		wp_register_script('ngg-progressbar', NGGALLERY_URLPATH .'admin/js/ngg.progressbar.js', array('jquery'), '2.0.1');
        wp_register_script('jquery-ui-autocomplete', NGGALLERY_URLPATH .'admin/js/jquery.ui.autocomplete.min.js', array('jquery-ui-core', 'jquery-ui-widget'), '1.8.15');

        // Enqueue the new Gritter-based progress bars
        wp_enqueue_style('ngg_progressbar');
        wp_enqueue_script('ngg_progressbar');

		switch ($_GET['page']) {
			case NGGFOLDER :
				wp_enqueue_script( 'postbox' );
				add_thickbox();
			break;
			case "nggallery-manage-gallery" :
				wp_enqueue_script( 'postbox' );
				wp_enqueue_script( 'ngg-ajax' );
				wp_enqueue_script( 'ngg-progressbar' );
				wp_enqueue_script( 'jquery-ui-dialog' );
				wp_enqueue_script( 'jquery-ui-sortable' );
    			wp_register_script('shutter', $router->get_static_url('photocrati-lightbox#shutter/shutter.js'), false ,'1.3.2');
    			wp_localize_script('shutter', 'shutterSettings', array(
    						'msgLoading' => __('L O A D I N G', 'nggallery'),
    						'msgClose' => __('Click to Close', 'nggallery'),
    						'imageCount' => '1'
    			) );
    			wp_enqueue_script( 'shutter' );
			break;
			case "nggallery-manage-album" :
                wp_enqueue_script( 'jquery-ui-autocomplete' );
                wp_enqueue_script( 'jquery-ui-dialog' );
                wp_enqueue_script( 'jquery-ui-sortable' );
                wp_enqueue_script( 'ngg-autocomplete', NGGALLERY_URLPATH .'admin/js/ngg.autocomplete.js', array('jquery-ui-autocomplete'), '1.0.1');
			break;
			case "nggallery-options" :
				wp_enqueue_script( 'jquery-ui-tabs' );
				//wp_enqueue_script( 'ngg-colorpicker', NGGALLERY_URLPATH .'admin/js/colorpicker/js/colorpicker.js', array('jquery'), '1.0');
			break;
			case "nggallery-add-gallery" :
				wp_enqueue_script( 'jquery-ui-accordion' );
				wp_enqueue_script( 'multifile', NGGALLERY_URLPATH .'admin/js/jquery.MultiFile.js', array('jquery'), '1.4.4' );
                wp_enqueue_script( 'ngg-plupload-handler' );
				wp_enqueue_script( 'ngg-ajax' );
				wp_enqueue_script( 'ngg-progressbar' );
                wp_enqueue_script( 'jquery-ui-dialog' );
				wp_enqueue_script( 'jqueryFileTree', NGGALLERY_URLPATH .'admin/js/jqueryFileTree/jqueryFileTree.js', array('jquery'), '1.0.1' );
			break;
			case "nggallery-style" :
				wp_enqueue_script( 'codepress' );
				wp_enqueue_script( 'ngg-colorpicker', NGGALLERY_URLPATH .'admin/js/colorpicker/js/colorpicker.js', array('jquery'), '1.0');
			break;

		}
	}


	function enqueue_jquery_ui_theme()
	{
		$settings = C_NextGen_Settings::get_instance();
		wp_enqueue_style(
			$settings->jquery_ui_theme,
			$settings->jquery_ui_theme_url,
			array(),
			$settings->jquery_ui_theme_version
		);
	}

	function load_styles() {
		global $ngg;

        // load the icon for the navigation menu
        wp_enqueue_style( 'nggmenu', NGGALLERY_URLPATH .'admin/css/menu.css', array() );
		wp_register_style( 'nggadmin', NGGALLERY_URLPATH .'admin/css/nggadmin.css', false, '2.8.1', 'screen' );
		wp_register_style( 'ngg-jqueryui', NGGALLERY_URLPATH .'admin/css/jquery.ui.css', false, '1.8.5', 'screen' );

        // no need to go on if it's not a plugin page
		if( !isset($_GET['page']) )
			return;

        // used to retrieve the uri of some module resources
        $router = C_Component_Registry::get_instance()->get_utility('I_Router');

		switch ($_GET['page']) {
			case NGGFOLDER :
				wp_enqueue_style( 'thickbox' );
			case "nggallery-about" :
				wp_enqueue_style( 'nggadmin' );
                //TODO:Remove after WP 3.3 release
                if ( !defined('IS_WP_3_3') )
                    wp_admin_css( 'css/dashboard' );
			break;
			case "nggallery-add-gallery":
				$this->enqueue_jquery_ui_theme();
				wp_enqueue_style( 'jqueryFileTree', NGGALLERY_URLPATH .'admin/js/jqueryFileTree/jqueryFileTree.css', false, '1.0.1', 'screen' );
			case "nggallery-options" :
				wp_enqueue_style( 'nggtabs', NGGALLERY_URLPATH .'admin/css/jquery.ui.tabs.css', false, '2.5.0', 'screen' );
				wp_enqueue_style( 'nggadmin' );
            break;
			case "nggallery-manage-gallery" :
                wp_enqueue_script('jquery-ui-tooltip');
                wp_enqueue_style('shutter', $router->get_static_url('photocrati-lightbox#shutter/shutter.css'), false, '1.3.2', 'screen');
			case "nggallery-roles" :
			case "nggallery-manage-album" :
				$this->enqueue_jquery_ui_theme();
				wp_enqueue_style( 'nggadmin' );
			break;
			case "nggallery-tags" :
				wp_enqueue_style( 'nggtags', NGGALLERY_URLPATH .'admin/css/tags-admin.css', false, '2.6.1', 'screen' );
				break;
			case "nggallery-style" :
				wp_admin_css( 'css/theme-editor' );
				wp_enqueue_style('nggcolorpicker', NGGALLERY_URLPATH.'admin/js/colorpicker/css/colorpicker.css', false, '1.0', 'screen');
				wp_enqueue_style('nggadmincp', NGGALLERY_URLPATH.'admin/css/nggColorPicker.css', false, '1.0', 'screen');
			break;
		}
	}

	function show_help($help, $screen) {

		// since WP3.0 it's an object
		if ( is_object($screen) )
			$screen = $screen->id;

		$link = '';
		// menu title is localized...
		$i18n = strtolower  ( _n( 'Gallery', 'Galleries', 1, 'nggallery' ) );

		switch ($screen) {
			case 'toplevel_page_' . NGGFOLDER :
				$link  = __('<a href="http://www.nextgen-gallery.com" target="_blank">Introduction</a>', 'nggallery');
			break;
			case "{$i18n}_page_nggallery-about" :
				$link  = __('<a href="http://www.nextgen-gallery.com/languages" target="_blank">Languages</a>', 'nggallery');
			break;
		}

		if ( !empty($link) ) {
			$help  = '<h5>' . __('Get help with NextGEN Gallery', 'nggallery') . '</h5>';
			$help .= '<div class="metabox-prefs">';
			$help .= $link;
			$help .= "</div>\n";
			$help .= '<h5>' . __('More Help & Info', 'nggallery') . '</h5>';
			$help .= '<div class="metabox-prefs">';
			$help .= __('<a href="http://wordpress.org/tags/nextgen-gallery?forum_id=10" target="_blank">Support Forums</a>', 'nggallery');
			$help .= ' | <a href="http://www.nextgen-gallery.com/faq/" target="_blank">' . __('FAQ', 'nggallery') . '</a>';
			$help .= ' | <a href="https://bitbucket.org/photocrati/nextgen-gallery/issues" target="_blank">' . __('Feature request', 'nggallery') . '</a>';
			$help .= ' | <a href="http://www.nextgen-gallery.com/languages" target="_blank">' . __('Get your language pack', 'nggallery') . '</a>';
			$help .= ' | <a href="https://bitbucket.org/photocrati/nextgen-gallery" target="_blank">' . __('Contribute development', 'nggallery') . '</a>';
			$help .= ' | <a href="http://wordpress.org/extend/plugins/nextgen-gallery" target="_blank">' . __('Download latest version', 'nggallery') . '</a>';
			$help .= "</div>\n";
		}

		return $help;
	}

    /**
     * New wrapper for WordPress 3.3, so contextual help will be added to the admin bar
     * Rework this see http://wpdevel.wordpress.com/2011/12/06/help-and-screen-api-changes-in-3-3/
     *
     * @since 1.9.0
     * @param object $screen
     * @return void
     */
    function add_contextual_help($screen) {

        $help = $this->show_help('', $screen);
        //add_contextual_help( $screen, $help );
    }

	/**
	 * We need to manipulate the current_screen name so that we can show the correct column screen options
	 *
     * @since 1.8.0
	 * @param object $screen
	 * @return object $screen
	 */
	function edit_current_screen($screen) {

    	if ( is_string($screen) )
    		$screen = convert_to_screen($screen);

		// menu title is localized, so we need to change the toplevel name
		$i18n = strtolower  ( _n( 'Gallery', 'Galleries', 1, 'nggallery' ) );

		switch ($screen->id) {
			case "{$i18n}_page_nggallery-manage-gallery" :
				// we would like to have screen option only at the manage images / gallery page
				if ( isset ($_POST['sortGallery']) )
					$screen = $screen;
				else if ( (isset($_GET['mode']) && $_GET['mode'] == 'edit') || isset ($_POST['backToGallery']) )
					$screen->base = $screen->id = 'nggallery-manage-images';
				else if ( (isset($_GET['mode']) && $_GET['mode'] == 'sort') )
					$screen = $screen;
				else
					$screen->base = $screen->id = 'nggallery-manage-gallery';
			break;
		}

        if ( defined('IS_WP_3_3') )
            $this->add_contextual_help($screen);

		return $screen;
	}

	/**
	 * We need to register the columns at a very early point
	 *
	 * @return void
	 */
	function register_columns() {
		include_once ( dirname (__FILE__) . '/manage-images.php' );

		$wp_list_table = new _NGG_Images_List_Table('nggallery-manage-images');

		include_once ( dirname (__FILE__) . '/manage-galleries.php' );

		$wp_list_table = new _NGG_Galleries_List_Table('nggallery-manage-gallery');
	}

	/**
	 * Read an array from a remote url
	 *
	 * @param string $url
	 * @return array of the content
	 */
	function get_remote_array($url) {

        if ( function_exists('wp_remote_request') ) {

            if ( false === ( $content = get_transient( 'ngg_request_' . md5($url) ) ) ) {

    			$options = array();
    			$options['headers'] = array(
    				'User-Agent' => 'NextGEN Gallery Information Reader V' . NGGVERSION . '; (' . get_bloginfo('url') .')'
    			 );

    			$response = wp_remote_request($url, $options);

    			if ( is_wp_error( $response ) )
    				return false;

    			if ( 200 != $response['response']['code'] )
    				return false;

                $content = $response['body'];
                set_transient( 'ngg_request_' . md5($url), $content, 60*60*48 );
            }

			$content = unserialize($content);

			if (is_array($content))
				return $content;
		}

		return false;
	}

}

function wpmu_site_admin() {
	// Check for site admin
	if ( function_exists('is_super_admin') )
		if ( is_super_admin() )
			return true;

	return false;
}

function wpmu_enable_function($value) {
	if (is_multisite()) {
		$ngg_options = get_site_option('ngg_options');
		return $ngg_options[$value];
	}
	// if this is not WPMU, enable it !
	return true;
}
