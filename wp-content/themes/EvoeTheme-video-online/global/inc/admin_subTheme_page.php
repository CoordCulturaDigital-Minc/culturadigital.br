<?php
include_once(TEMPLATEPATH .   '/global/inc/custom_theme.php');

class admin_subTheme_page
{
	public $bg;

	/* Constructor */
	function admin_subTheme_page()
	{
		global $custom_theme;
		$this->bg = get_option('custom_theme');
		
		if( !is_array($this->bg) )
		{
			$this->bg = array( 
				'background' => array( 'color' => '', 'position' => '', 'repeat' => '', 'attachment' => '' ),
				'header' => array( 'text' => '', 'textColor' => '', 'logoPosition' => '', 'color' => '', 'imagePosition' => '', 'imageRepeat' => '' ),
				'box' => array( 'color' => '', 'textColor' => '', 'pattern' => '', 'patternOpacity' => '' ),
				'content' => array( 'linkColor' => '' )
			);
			
			$general = array(
					'custom_theme' => 'theme1',
					'custom_themeD' => '1',
					'hlCategory' => '1'
			);
			
			update_option('custom_theme', $this->bg);
			
			$custom_theme->set_customTheme_general( $general );
		}
		
    	add_action('admin_menu', array(&$this, 'admin_page'));
		add_action('admin_print_scripts-appearance_page_admin_subTheme_page', array(&$this, 'active_scripts_page'));
		add_action('admin_print_styles-appearance_page_admin_subTheme_page', array(&$this, 'active_styles'));
	}
	
	/* Add page in wp-admin menu */
	function admin_page()
	{
		add_theme_page(__("Theme options"), __("Theme options"), "edit_themes", basename(__FILE__), array(&$this, 'admin_custom_page'));
	}
	
	
	/* Javascript */
	function active_scripts_page()
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-tabs', 'jquery');
		wp_enqueue_script('ajaxUpload', get_bloginfo('stylesheet_directory') .'/global/js/ajaxupload.js', $priority = 19, 'jquery');
		wp_enqueue_script('jqueryCookie', get_bloginfo('stylesheet_directory') .'/global/js/jquery.cookie.js', $priority = 15, 'jquery');
		wp_enqueue_script('jcolorpicker', get_bloginfo('stylesheet_directory') .'/global/js/colorpicker.js', $priority = 15, 'jquery');		
		wp_enqueue_script('jquery-dependClass', get_bloginfo('stylesheet_directory') .'/global/js/jquery.dependClass.js', $priority = 15, 'jquery');
		wp_enqueue_script('jquery-slider', get_bloginfo('stylesheet_directory') .'/global/js/jquery.slider-min.js', $priority = 15, 'jquery, jquery-dependClass');
		wp_enqueue_script('admin_subTheme_page', get_bloginfo('stylesheet_directory') .'/global/inc/admin_subTheme_page.js.php', $priority = 10, 'jquery');
	}
	
	/* stylesheet */
	function active_styles()
	{
		wp_enqueue_style('colorpicker', get_bloginfo('stylesheet_directory') .'/global/css/colorpicker.css');
		wp_enqueue_style('jslider', get_bloginfo('stylesheet_directory') .'/global/css/jslider/jslider.css');
		wp_enqueue_style('jslider-blue', get_bloginfo('stylesheet_directory') .'/global/css/jslider/jslider.blue.css');
		wp_enqueue_style('admin_subTheme_page', get_bloginfo('stylesheet_directory') .'/global/css/admin_subTheme_page.css');
		
		?>
            <!--[if IE 6]>
                <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/global/css/jslider/jslider.ie6.css" type="text/css" media="screen">
                <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/global/css/jslider/jslider.blue.ie6.css" type="text/css" media="screen">
            <![endif]-->
        <?php
	}
	
	
	/* wp-admin page
	___________________*/
	
	function admin_custom_page()
	{
		?>
			<div class="wrap custom_theme">
                <h2><?php _e('Theme options', 'evoeTheme'); ?></h2>
				
                <form class="custom_theme_form" action="<?php bloginfo('template_directory'); ?>/global/inc/admin_custom_theme.php" method="post" enctype="multipart/form-data">
                    <ul class="tabs">
                    	<li><a class="bt_general" href="#skins" class=""><?php _e('Skins', 'evoeTheme'); ?></a></li>
                        <li><a class="bt_design" href="#design" class=""><?php _e('Design', 'evoeTheme'); ?></a></li>
                        <li><a class="bt_general" href="#general_" class=""><?php _e('Global', 'evoeTheme'); ?></a></li>
                    </ul>
                    
                    <div class="preview" style="display:none;">
                        <div id="preview">
                            <iframe id="themepreview" src="<?php bloginfo('url'); ?>?preview=true" name="themepreview"></iframe>
                        </div>
                    </div>
                    
                    <div id="skins">
                    	<?php if( $this->bg['name'] ) : ?>
                        	<h4><?php _e('Active theme', 'evoeTheme'); ?>:</h4>
                            <div class="defaultThemes">
                                <a href="#" title="" class="screenshot sc-target" rel="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/<?php echo $this->bg['name'] . '/' . $this->bg['name'] . '-lg.jpg'; ?>">
                                    <span class="theme1">
                                        <img src="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/<?php echo $this->bg['name'] . '/' . $this->bg['name'] . '.jpg'; ?>" alt="" />
                                    </span>
                                </a>
                            </div>
                        <?php endif; ?>
                    	<h4><?php _e('Default themes', 'evoeTheme'); ?>:</h4>
                    	<ul class="defaultThemes">
                        	<li>
                            	<a href="#" title="" class="screenshot sc-target" rel="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme1/theme1-lg.jpg">
                                    <span class="theme1">
                                        <img src="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme1/theme1.jpg" alt="" />
                                    </span>
                                </a>
                            </li>
                        	<li>
                            	<a href="#" title="" class="screenshot sc-target" rel="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme2/theme2-lg.jpg">
                                    <span class="theme2">
                                        <img src="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme2/theme2.jpg" alt="" />
                                    </span>
                                </a>
                            </li>
                        	<li>
                            	<a href="#" title="" class="screenshot" rel="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme3/theme3-lg.jpg">
                                    <span class="theme3">
                                        <img src="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme3/theme3.jpg" alt="" />
                                    </span>
                                </a>
                            </li>
                        	<li>
                            	<a href="#" title="" class="screenshot" rel="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme4/theme4-lg.jpg">
                                    <span class="theme4">
                                        <img src="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme4/theme4.jpg" alt="" />
                                    </span>
                                </a>
                            </li>
                        	<li>
                            	<a href="#" title="" class="screenshot" rel="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme5/theme5-lg.jpg">
                                    <span class="theme5">
                                        <img src="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme5/theme5.jpg" alt="" />
                                    </span>
                                </a>
                            </li>
                        	<li>
                            	<a href="#" title="" class="screenshot" rel="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme6/theme6-lg.jpg">
                                    <span class="theme6">
                                        <img src="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme6/theme6.jpg" alt="" />
                                    </span>
                                </a>
                            </li>
                        	<li>
                            	<a href="#" title="" class="screenshot" rel="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme7/theme7-lg.jpg">
                                    <span class="theme7">
                                        <img src="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme7/theme7.jpg" alt="" />
                                    </span>
                                </a>
                            </li>
                        	<li>
                            	<a href="#" title="" class="screenshot" rel="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme8/theme8-lg.jpg">
                                    <span class="theme8">
                                        <img src="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme8/theme8.jpg" alt="" />
                                    </span>
                                </a>
                            </li>
                        	<li>
                            	<a href="#" title="" class="screenshot" rel="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme9/theme9-lg.jpg">
                                    <span class="theme9">
                                        <img src="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme9/theme9.jpg" alt="" />
                                    </span>
                                </a>
                            </li>
                        	<li>
                            	<a href="#" title="" class="screenshot" rel="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme10/theme10-lg.jpg">
                                    <span class="theme10">
                                        <img src="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme10/theme10.jpg" alt="" />
                                    </span>
                                </a>
                            </li>
                        	<li>
                            	<a href="#" title="" class="screenshot" rel="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme11/theme11-lg.jpg">
                                    <span class="theme11">
                                        <img src="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme11/theme11.jpg" alt="" />
                                    </span>
                                </a>
                            </li>
                        	<li>
                            	<a href="#" title="" class="screenshot" rel="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme12/theme12-lg.jpg">
                                    <span class="theme12">
                                        <img src="<?php bloginfo('template_directory'); ?>/global/img/graph/skins/theme12/theme12.jpg" alt="" />
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <input type="hidden" name="custom_theme" id="custom_themes" value="<?php print $this->bg['name']; ?>" />
                        <input type="hidden" name="custom_themeD" id="custom_themesD" value="0" />
                        <div class="clear"></div>
                    </div>
                    
                    <div id="design">
                        <ul class="design">
                            <li><a href="#bg"><?php _e('Background', 'evoeTheme'); ?></a></li>
                            <li><a href="#headerDesign"><?php _e('Header', 'evoeTheme'); ?></a></li>
                            <li><a href="#content"><?php _e('Content', 'evoeTheme'); ?></a></li>
                        </ul>
                        
                        <div id="bg">
                            <div class="bgColor color">
                                <h4><?php _e('Choose the background color', 'evoeTheme'); ?>:</h4>
                                <ul>
                                    <li>
                                        <label for="color0"><span>#ffffff</span></label><input id="color0" type="radio" checked="checked" name="bgColorCheck" value="#ffffff" />
                                    </li>
                                    <li>
                                        <label for="color1"><span>#8e4a24</span></label><input id="color1" type="radio" name="bgColorCheck" value="#8e4a24" />
                                    </li>
                                    <li>
                                        <label for="color2"><span>#1a1a1a</span></label><input id="color2" type="radio" name="bgColorCheck" value="#1a1a1a" />
                                    </li>
                                    <li>
                                        <label for="color3"><span>#2d552c</span></label><input id="color3" type="radio" name="bgColorCheck" value="#2d552c" />
                                    </li>
                                    <li>
                                        <label for="color4"><span>#6b2328</span></label><input id="color4" type="radio" name="bgColorCheck" value="#6b2328" />
                                    </li>
                                    <li>
                                        <label for="color5"><span>#5f5c2e</span></label><input id="color5" type="radio" name="bgColorCheck" value="#5f5c2e" />
                                    </li>
                                    <li>
                                        <label for="color6"><span>#5e3156</span></label><input id="color6" type="radio" name="bgColorCheck" value="#5e3156" />
                                    </li>
                                    <li>
                                        <label for="color7"><span>#000000</span></label><input id="color7" type="radio" name="bgColorCheck" value="#000000" />
                                    </li>
                                    <li>
                                        <label for="color8"><span>#026d9b</span></label><input id="color8" type="radio" name="bgColorCheck" value="#026d9b" />
                                    </li>
                                    <li class="picker">
                                    	<div class="colorpicker_">
                                    		<div>
                                    		</div>
                                    	</div>
                                       <input type="hidden" class="colorpicker" name="bgColor" />
                                       <div class="bgPicker">
                                       </div>
                                    </li>
                                </ul>
                                <div class="clear"></div>
                            </div>
                        
                            <div class="upload">
                                <h4><?php _e('Choose the background image', 'evoeTheme'); ?>:</h4>
                                <div id="uploadBg" class="uploadFromHD" <?php if(empty($this->bg['background']['url'])) : ?>style="display:block;"<?php else : ?>style="display:none;"<?php endif; ?>>
                                    <label><?php _e('Maximum file size is 800kb. Supported file types: png, jpg, gif.', 'evoeTheme'); ?></label>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="800000" />
                                    <input type="button" id="bgImage" value="<?php _e('Upload', 'evoeTheme'); ?>" />
                                </div>
                                <div id="restoreBg" class="uploadFromHD" <?php if(!empty($this->bg['background']['url'])) : ?>style="display:block;"<?php else : ?>style="display:none;"<?php endif; ?>>
                                    <label class="block"><?php _e('Restore image', 'evoeTheme'); ?>: </label>
                                    <input id="restoreBg" type="submit" name="restoreBg" value="<?php _e('Restore', 'evoeTheme'); ?>" />
                                </div>
                            </div>
                    
                    		<div class="bgOpt" <?php if(!empty($this->bg['background']['url'])) : ?>style="display:block; margin-top:-25px;"<?php else : ?>style="display:none; margin-top:-25px;"<?php endif; ?>>
	                            <h4 class="tit_"><?php _e('Background image options', 'evoeTheme'); ?>:</h4>
                                <div class="bgPosition">
                                    <h4><?php _e('Positioning', 'evoeTheme'); ?>:</h4>
                                    <ul>
                                        <li><label><input type="radio" name="bgPosition" value="center top" /> <?php _e('center top', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="bgPosition" value="center right" /> <?php _e('center right', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="bgPosition" value="center left" /> <?php _e('center left', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="bgPosition" value="center bottom" /> <?php _e('center bottom', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="bgPosition" checked="checked" value="left top" /> <?php _e('left top', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="bgPosition" value="left bottom" /> <?php _e('left bottom', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="bgPosition" value="right top" /> <?php _e('right top', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="bgPosition" value="right bottom" /> <?php _e('right bottom', 'evoeTheme'); ?></label></li>
                                    </ul>
                                </div>
                                <div class="bgRepeat">
                                    <h4><?php _e('Tile', 'evoeTheme'); ?>:</h4>
                                    <ul>
                                        <li><label><input type="radio" name="bgRepeat" checked="checked" value="repeat" /> <?php _e('tile', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="bgRepeat" value="repeat-x" /> <?php _e('tile horizontally', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="bgRepeat" value="repeat-y" /> <?php _e('tile vertically', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="bgRepeat" value="no-repeat" /> <?php _e('no tile', 'evoeTheme'); ?></label></li>
                                    </ul>
                                </div>
                                <div id="bgAttachment" class="bgRepeat">
                                    <h4><?php _e('Attachment', 'evoeTheme'); ?>:</h4>
                                    <ul>
                                        <li><label><input type="radio" name="bgAttachment" value="fixed" /> <?php _e('fixed', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="bgAttachment" checked="checked" value="scroll" /> <?php _e('scroll', 'evoeTheme'); ?></label></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        
                        <div id="headerDesign">
                        	<div  style="margin-left:15px;">
								<h4 style="margin-top:25px; margin-bottom:0;"><?php _e('Choose the navigation bar opacity', 'evoeTheme'); ?>:</h4>
								<div class="layout-slider" style="width:360px; margin-top:20px; margin-bottom:15px;">
									<input id="SliderSingle2" type="slider" name="menuOpacity" value="<?php if(!empty($this->bg['header']['menuOpacity'])) { print $this->bg['header']['menuOpacity']; } else { ?>80 <?php } ?>" />
								</div>
							</div>
							
                        	<div class="titleDisplay">
                                <h4><?php _e('Display blog title?', 'evoeTheme') ?></h4>
                                <ul>
                                    <li><label><input type="radio" checked="checked" name="headerTitle" value="0" /> <?php _e('yes', 'evoeTheme'); ?></label></li>
                                    <li><label><input type="radio" name="headerTitle" value="-999em" /> <?php _e('no', 'evoeTheme'); ?></label></li>
                                </ul>
                            </div>
                            
                            <div class="titleColor color" <?php if( $this->bg['header']['text'] == '-999em' ): ?> style="display:none" <?php endif; ?>>
                                <h4><?php _e('Choose title color', 'evoeTheme'); ?>:</h4>
                                <ul>
                                    <li>
                                        <label for="titleColor0"><span>#ffffff</span></label><input id="titleColor0" type="radio" name="titleColor" value="#ffffff" />
                                    </li>
                                    <li>
                                        <label for="titleColor1"><span>#8e4a24</span></label><input id="titleColor1" type="radio" name="titleColor" value="#8e4a24" />
                                    </li>
                                    <li>
                                        <label for="titleColor2"><span>#1a1a1a</span></label><input id="titleColor2" type="radio" name="titleColor" value="#1a1a1a" />
                                    </li>
                                    <li>
                                        <label for="titleColor3"><span>#2d552c</span></label><input id="titleColor3" type="radio" name="titleColor" value="#2d552c" />
                                    </li>
                                    <li>
                                        <label for="titleColor4"><span>#6b2328</span></label><input id="titleColor4" type="radio" name="titleColor" value="#6b2328" />
                                    </li>
                                    <li>
                                        <label for="titleColor5"><span>#5f5c2e</span></label><input id="titleColor5" type="radio" name="titleColor" value="#5f5c2e" />
                                    </li>
                                    <li>
                                        <label for="titleColor6"><span>#5e3156</span></label><input id="titleColor6" type="radio" name="titleColor" value="#5e3156" />
                                    </li>
                                    <li>
                                        <label for="titleColor7"><span>#000000</span></label><input id="titleColor7" type="radio" name="titleColor" value="#000000" />
                                    </li>
                                    <li>
                                        <label for="titleColor8"><span>#026d9b</span></label><input id="titleColor8" type="radio" name="titleColor" value="#026d9b" />
                                    </li>
                                    <li class="picker">
                                    	<div class="colorpicker_">
                                    		<div>
                                    		</div>
                                    	</div>
                                       <input type="hidden" class="colorpicker" name="titleColorInput" />
                                       <div class="titlePicker">
                                       </div>
                                    </li>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            
                            <div class="upload">
                                <h4><?php _e('Choose logo image', 'evoeTheme'); ?>:</h4>
                                <div id="uploadLogo" class="uploadFromHD" <?php if(empty($this->bg['header']['logo'])) : ?>style="display:block;"<?php else : ?>style="display:none;"<?php endif; ?>>
                                    <label><?php _e('Maximum file size is 800kb. Supported file types: png, jpg, gif. Maximum file dimensions: 960px x 125px', 'evoeTheme'); ?></label>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="800000" />
                                    <input type="button" id="logoImage" value="<?php _e('Upload', 'evoeTheme'); ?>" />
                                </div>
                                <div id="restoreLogo" class="uploadFromHD" <?php if(!empty($this->bg['header']['logo'])) : ?>style="display:block;"<?php else : ?>style="display:none;"<?php endif; ?>>
                                    <label class="block"><?php _e('Restore image', 'evoeTheme'); ?>: </label>
                                    <input id="retoreLogo" type="submit" name="restoreLogo" value="<?php _e('Restore', 'evoeTheme'); ?>" />
                                </div>
                            </div>
                			<h4 class="tit_"><?php _e('Logo options', 'evoeTheme'); ?>:</h4>
                            <div id="logoPosition" class="bgPosition" <?php if(!empty($this->bg['header']['logo'])) : ?>style="display:block;"<?php else : ?>style="display:none;"<?php endif; ?>>
                                <h4><?php _e('Logo positioning', 'evoeTheme'); ?>:</h4>
                                <ul>
                                    <li><label><input type="radio" name="logoPosition" value="center" /> <?php _e('center', 'evoeTheme'); ?></label></li>
                                    <li><label><input type="radio" name="logoPosition" value="left center" checked="checked" /> <?php _e('left', 'evoeTheme'); ?></label></li>
                                    <li><label><input type="radio" name="logoPosition" value="right center" /> <?php _e('right', 'evoeTheme'); ?></label></li>
                                </ul>
                            </div>
                            
                            <div class="clear"></div>
                            
                            <div class="headerColor color">
                                <h4><?php _e('Choose header color', 'evoeTheme'); ?>:</h4>
                                <ul>
                                    <li>
                                        <label for="headerColor" class="none"><span>X</span></label><input id="headerColor" type="radio" name="headerColor" value="#transparent" />
                                    </li>
                                    <li>
                                        <label for="headerColor0"><span>#ffffff</span></label><input id="headerColor0" type="radio" name="headerColor" value="#ffffff" />
                                    </li>
                                    <li>
                                        <label for="headerColor1"><span>#8e4a24</span></label><input id="headerColor1" type="radio" name="headerColor" value="#8e4a24" />
                                    </li>
                                    <li>
                                        <label for="headerColor2"><span>#1a1a1a</span></label><input id="headerColor2" type="radio" name="headerColor" value="#1a1a1a" />
                                    </li>
                                    <li>
                                        <label for="headerColor3"><span>#2d552c</span></label><input id="headerColor3" type="radio" name="headerColor" value="#2d552c" />
                                    </li>
                                    <li>
                                        <label for="headerColor4"><span>#6b2328</span></label><input id="headerColor4" type="radio" name="headerColor" value="#6b2328" />
                                    </li>
                                    <li>
                                        <label for="headerColor5"><span>#5f5c2e</span></label><input id="headerColor5" type="radio" name="headerColor" value="#5f5c2e" />
                                    </li>
                                    <li>
                                        <label for="headerColor6"><span>#5e3156</span></label><input id="headerColor6" type="radio" name="headerColor" value="#5e3156" />
                                    </li>
                                    <li>
                                        <label for="headerColor7"><span>#000000</span></label><input id="headerColor7" type="radio" name="headerColor" value="#000000" />
                                    </li>
                                    <li>
                                        <label for="headerColor8"><span>#026d9b</span></label><input id="headerColor8" type="radio" name="headerColor" value="#026d9b" />
                                    </li>
                                    <li class="picker">
                                    	<div class="colorpicker_">
                                    		<div>
                                    		</div>
                                    	</div>
                                       <input type="hidden" class="colorpicker" name="headerColorInput" />
                                       <div class="headerPicker">
                                       </div>
                                    </li>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            
                            <div class="upload">
                                <h4><?php _e('Choose a header background image', 'evoeTheme'); ?>:</h4>
                                <div id="uploadHeader" class="uploadFromHD" <?php if(empty($this->bg['header']['image'])) : ?>style="display:block;"<?php else : ?>style="display:none;"<?php endif; ?>>
                                    <label><?php _e('Maximum file size is 800kb. Supported file types: png, jpg, gif. maximum file dimensions: 1920px x 125px', 'evoeTheme'); ?></label>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="800000" />
                                    <input type="button" id="headerImage" value="<?php _e('Upload', 'evoeTheme'); ?>" />
                                </div>
                                <div id="restoreHeader" class="uploadFromHD" <?php if(!empty($this->bg['header']['image'])) : ?>style="display:block;"<?php else : ?>style="display:none;"<?php endif; ?>>
                                    <label class="block"><?php _e('Restore image', 'evoeTheme'); ?>: </label>
                                    <input id="retoreHeader" type="submit" name="restoreHeader" value="<?php _e('Restore', 'evoeTheme'); ?>" />
                                </div>
                            </div>
                    
                    		<div id="headerOpt" <?php if(!empty($this->bg['header']['image'])) : ?>style="display:block;"<?php else : ?>style="display:none;"<?php endif; ?>>
                            	<h4 class="tit_"><?php _e('Header background image options', 'evoeTheme'); ?></h4>
                                <div id="headerPosition" class="bgPosition">
                                    <h4><?php _e('Posicioning', 'evoeTheme'); ?>:</h4>
                                    <ul>
                                        <li><label><input type="radio" name="headerPosition" value="center top" /> <?php _e('center top', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="headerPosition" value="center right" /> <?php _e('center right', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="headerPosition" value="center left" /> <?php _e('center left', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="headerPosition" value="center bottom" /> <?php _e('center bottom', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="headerPosition" checked="checked" value="left top" /> <?php _e('left top', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="headerPosition" value="left bottom" /> <?php _e('left bottom', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="headerPosition" value="right top" /> <?php _e('right top', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="headerPosition" value="right bottom" /> <?php _e('right bottom', 'evoeTheme'); ?></label></li>
                                    </ul>
                                </div>
                                <div id="headerRepeat" class="bgRepeat">
                                    <h4><?php _e('Tile', 'evoeTheme'); ?>:</h4>
                                    <ul>
                                        <li><label><input type="radio" name="headerRepeat" checked="checked" value="repeat" /> <?php _e('tile', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="headerRepeat" value="repeat-x" /> <?php _e('tile horizontally', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="headerRepeat" value="repeat-y" /> <?php _e('tile vertically', 'evoeTheme'); ?></label></li>
                                        <li><label><input type="radio" name="headerRepeat" value="no-repeat" /> <?php _e('no tile', 'evoeTheme'); ?></label></li>
                                    </ul>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        
                        <div id="content">
                            <div class="boxOpt">
                                <div id="boxColor" class="boxColor color">
                                    <h4><?php _e('Choose title box background color', 'evoeTheme'); ?>:</h4>
                                    <ul>
                                        <li>
                                            <label for="boxColor0"><span>#ffffff</span></label><input id="boxColor0" type="radio" name="boxColorCheck" value="#ffffff" />
                                        </li>
                                        <li>
                                            <label for="boxColor1"><span>#8e4a24</span></label><input id="boxColor1" type="radio" name="boxColorCheck" value="#8e4a24" />
                                        </li>
                                        <li>
                                            <label for="boxColor2"><span>#1a1a1a</span></label><input id="boxColor2" type="radio" name="boxColorCheck" value="#1a1a1a" />
                                        </li>
                                        <li>
                                            <label for="boxColor3"><span>#2d552c</span></label><input id="boxColor3" type="radio" name="boxColorCheck" value="#2d552c" />
                                        </li>
                                        <li>
                                            <label for="boxColor4"><span>#6b2328</span></label><input id="boxColor4" type="radio" name="boxColorCheck" value="#6b2328" />
                                        </li>
                                        <li>
                                            <label for="boxColor5"><span>#5f5c2e</span></label><input id="boxColor5" type="radio" name="boxColorCheck" value="#5f5c2e" />
                                        </li>
                                        <li>
                                            <label for="boxColor6"><span>#5e3156</span></label><input id="boxColor6" type="radio" name="boxColorCheck" value="#5e3156" />
                                        </li>
                                        <li>
                                            <label for="boxColor7"><span>#000000</span></label><input id="boxColor7" type="radio" name="boxColorCheck" value="#000000" />
                                        </li>
                                        <li>
                                            <label for="boxColor8"><span>#026d9b</span></label><input id="boxColor8" type="radio" name="boxColorCheck" value="#026d9b" />
                                        </li>
	                                    <li class="picker">
	                                    	<div class="colorpicker_">
	                                    		<div>
	                                    		</div>
	                                    	</div>
	                                       <input type="hidden" class="colorpicker" name="boxColor" />
	                                       <div class="boxPicker">
	                                       </div>
	                                    </li>
                                    </ul>
                                    <div class="clear"></div>
                                </div>
                                <div id="textColor" class="textColor color">
                                    <h4><?php _e('Choose the title box text color', 'evoeTheme'); ?>:</h4>
                                    <ul>
                                        <li>
                                            <label for="textColor0"><span>#ffffff</span></label><input id="textColor0" type="radio" name="textColorCheck" value="#ffffff" />
                                        </li>
                                        <li>
                                            <label for="textColor1"><span>#8e4a24</span></label><input id="textColor1" type="radio" name="textColorCheck" value="#8e4a24" />
                                        </li>
                                        <li>
                                            <label for="textColor2"><span>#1a1a1a</span></label><input id="textColor2" type="radio" name="textColorCheck" value="#1a1a1a" />
                                        </li>
                                        <li>
                                            <label for="textColor3"><span>#2d552c</span></label><input id="textColor3" type="radio" name="textColorCheck" value="#2d552c" />
                                        </li>
                                        <li>
                                            <label for="textColor4"><span>#6b2328</span></label><input id="textColor4" type="radio" name="textColorCheck" value="#6b2328" />
                                        </li>
                                        <li>
                                            <label for="textColor5"><span>#5f5c2e</span></label><input id="textColor5" type="radio" name="textColorCheck" value="#5f5c2e" />
                                        </li>
                                        <li>
                                            <label for="textColor6"><span>#5e3156</span></label><input id="textColor6" type="radio" name="textColorCheck" value="#5e3156" />
                                        </li>
                                        <li>
                                            <label for="textColor7"><span>#000000</span></label><input id="textColor7" type="radio" name="textColorCheck" value="#000000" />
                                        </li>
                                        <li>
                                            <label for="textColor8"><span>#026d9b</span></label><input id="textColor8" type="radio" name="textColorCheck" value="#026d9b" />
                                        </li>
	                                    <li class="picker">
	                                    	<div class="colorpicker_">
	                                    		<div>
	                                    		</div>
	                                    	</div>
	                                       <input type="hidden" class="colorpicker" name="textColor" />
	                                       <div class="boxTextPicker">
	                                       </div>
	                                    </li>
                                    </ul>
                                    <div class="clear"></div>
                                </div>
                                <div id="pattern" class="pattern">
                                	<div id="patternOpt" <?php if(empty($this->bg['box']['patternId'])) : ?>style="display:block;"<?php else : ?>style="display:none;"<?php endif; ?>>
                                        <h4><?php _e('Choose a pattern for the title box', 'evoeTheme'); ?>:</h4>
                                        <ul>
                                            <li>
                                                <label for="patternNone" class="none"><span>X</span></label><input id="patternNone" type="radio" name="patternCheck" value="none" />
                                            </li>
                                            <li>
                                                <label for="pattern1"><img src="<?php bloginfo('template_directory'); ?>/global/img/graph/patterns/graph_pattern1.gif" alt="pattern1" /></label><input id="pattern1" type="radio" name="patternCheck" value="pattern1.gif" />
                                            </li>
                                            <li>
                                                <label for="pattern2"><img src="<?php bloginfo('template_directory'); ?>/global/img/graph/patterns/graph_pattern2.gif" alt="pattern2" /></label><input id="pattern2" type="radio" name="patternCheck" value="pattern2.gif" />
                                            </li>
                                            <li>
                                                <label for="pattern3"><img src="<?php bloginfo('template_directory'); ?>/global/img/graph/patterns/graph_pattern3.gif" alt="pattern3" /></label><input id="pattern3" type="radio" name="patternCheck" value="pattern3.gif" />
                                            </li>
                                            <li>
                                                <label for="pattern4"><img src="<?php bloginfo('template_directory'); ?>/global/img/graph/patterns/graph_pattern4.gif" alt="pattern4" /></label><input id="pattern4" type="radio" name="patternCheck" value="pattern4.gif" />
                                            </li>
                                            <li>
                                                <label for="pattern5"><img src="<?php bloginfo('template_directory'); ?>/global/img/graph/patterns/graph_pattern5.gif" alt="pattern5" /></label><input id="pattern5" type="radio" name="patternCheck" value="pattern5.gif" />
                                            </li>
                                            <li>
                                                <label for="pattern6"><img src="<?php bloginfo('template_directory'); ?>/global/img/graph/patterns/graph_pattern6.gif" alt="pattern6" /></label><input id="pattern6" type="radio" name="patternCheck" value="pattern6.gif" />
                                            </li>
                                            <li>
                                                <label for="pattern7"><img src="<?php bloginfo('template_directory'); ?>/global/img/graph/patterns/graph_pattern7.gif" alt="pattern7" /></label><input id="pattern7" type="radio" name="patternCheck" value="pattern7.gif" />
                                            </li>
                                            <li>
                                                <label for="pattern8"><img src="<?php bloginfo('template_directory'); ?>/global/img/graph/patterns/graph_pattern8.gif" alt="pattern8" /></label><input id="pattern8" type="radio" name="patternCheck" value="pattern8.gif" />
                                            </li>
                                            <li>
                                                <label for="pattern9"><img src="<?php bloginfo('template_directory'); ?>/global/img/graph/patterns/graph_pattern9.gif" alt="pattern9" /></label><input id="pattern9" type="radio" name="patternCheck" value="pattern9.gif" />
                                            </li>
                                            <li>
                                                <label for="pattern10"><img src="<?php bloginfo('template_directory'); ?>/global/img/graph/patterns/graph_pattern10.gif" alt="pattern10" /></label><input id="pattern10" type="radio" name="patternCheck" value="pattern10.gif" />
                                            </li>
                                            <li>
                                                <label for="pattern11"><img src="<?php bloginfo('template_directory'); ?>/global/img/graph/patterns/graph_pattern11.gif" alt="pattern11" /></label><input id="pattern11" type="radio" name="patternCheck" value="pattern11.gif" />
                                            </li>
                                        </ul>
                                    </div>
                                    <input type="text" id="patternValue" name="patternValue" />
                                    <div class="patternImage">
                                        <h4><?php _e('Choose an image for the title box pattern', 'evoeTheme'); ?>:</h4>
                                        <div id="uploadPattern" class="uploadFromHD" <?php if(empty($this->bg['box']['patternId'])) : ?>style="display:block;"<?php else : ?>style="display:none;"<?php endif; ?>>
                                            <label><?php _e('Maximum file size is 150kb. Supported file types: png, jpg, gif. Maximum file dimensions: 630px x 36px', 'evoeTheme'); ?></label>
                                            <input type="hidden" name="MAX_FILE_SIZE" value="150000" />
                                            <input type="button" id="patternImage" value="<?php _e('Upload', 'evoeTheme'); ?>" />
                                        </div>
                                        <div id="restorePattern" class="uploadFromHD" <?php if(!empty($this->bg['box']['patternId'])) : ?>style="display:block;"<?php else : ?>style="display:none;"<?php endif; ?>>
                                            <label class="block"><?php _e('Restore image', 'evoeTheme'); ?>: </label>
                                            <input id="restorePattern" type="submit" name="restorePattern" value="<?php _e('Restore', 'evoeTheme'); ?>" />
                                        </div>
                                    </div>
                                    <h4><?php _e('Choose the title box pattern opacity', 'evoeTheme'); ?>:</h4>
                                    <div class="layout-slider" style="width:360px; margin-left:15px; margin-top:30px;">
                                        <input id="SliderSingle3" type="slider" name="boxOpacity" value="<?php if(!empty($this->bg['box']['patternOpacity'])) { print $this->bg['box']['patternOpacity']; } else { ?>65 <?php } ?>" />
                                    </div>
                                </div>
                            </div>
                        
                            <div id="linkColor" class="linkColor color">
                                <h4><?php _e('Choose blog links color', 'evoeTheme'); ?>:</h4>
                                <ul>
                                    <li>
                                        <label for="linkColor0"><span>#ffffff</span></label><input id="linkColor0" checked="checked" type="radio" name="linkColorCheck" value="ffffff" />
                                    </li>
                                    <li>
                                        <label for="linkColor1"><span>#8e4a24</span></label><input id="linkColor1" type="radio" name="linkColorCheck" value="8e4a24" />
                                    </li>
                                    <li>
                                        <label for="linkColor2"><span>#1a1a1a</span></label><input id="linkColor2" type="radio" name="linkColorCheck" value="1a1a1a" />
                                    </li>
                                    <li>
                                        <label for="linkColor3"><span>#2d552c</span></label><input id="linkColor3" type="radio" name="linkColorCheck" value="2d552c" />
                                    </li>
                                    <li>
                                        <label for="linkColor4"><span>#6b2328</span></label><input id="linkColor4" type="radio" name="linkColorCheck" value="6b2328" />
                                    </li>
                                    <li>
                                        <label for="linkColor5"><span>#5f5c2e</span></label><input id="linkColor5" type="radio" name="linkColorCheck" value="5f5c2e" />
                                    </li>
                                    <li>
                                        <label for="linkColor6"><span>#5e3156</span></label><input id="linkColor6" type="radio" name="linkColorCheck" value="5e3156" />
                                    </li>
                                    <li>
                                        <label for="linkColor7"><span>#000000</span></label><input id="linkColor7" type="radio" name="linkColorCheck" value="000000" />
                                    </li>
                                    <li>
                                        <label for="linkColor8"><span>#026d9b</span></label><input id="linkColor8" type="radio" name="linkColorCheck" value="026d9b" />
                                    </li>
                                    <li class="picker">
                                    	<div class="colorpicker_">
                                    		<div>
                                    		</div>
                                    	</div>
                                       <input type="hidden" class="colorpicker" name="linkColor" />
                                       <div class="linkPicker">
                                       </div>
                                    </li>
                                </ul>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="general_">
                    	<div class="category_">
                    		<label for="hlCategory"><?php _e('Choose a featured category', 'evoeTheme'); ?>:</label>
							<?php wp_dropdown_categories('show_count=1&hierarchical=1&name=hlCategory&selected='. $this->bg['general']['hlCategory']); ?>
                    	</div>
                        <div class="clear"></div>
                    </div>
                    
                    <div class="buttons">
                        <input type="submit" name="restore" class="bt_restore" value="<?php _e('Restore', 'evoeTheme'); ?>" />
                        <input type="hidden" class="action_" name="action_" />
                        <input type="submit" name="action" class="bt_save" value="<?php _e('Save', 'evoeTheme'); ?>" />
                    </div>
                </form>
            </div>
        </div>
	<?php
	}
	
}

$admin_subTheme_page = new admin_subTheme_page();
