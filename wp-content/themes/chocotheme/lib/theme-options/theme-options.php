<?php
if (!function_exists('array_combine')) {
	function array_combine($arr1, $arr2) {
	    $out = array();
	   
	    $arr1 = array_values($arr1);
	    $arr2 = array_values($arr2);
	   
	    foreach($arr1 as $key1 => $value1) {
	        $out[(string)$value1] = $arr2[$key1];
	    }
	   
	    return $out;
	}
}
include_once('option-fields.php');
class OptionsPage {
	var $options;
	var $tpl_vars = array();
	var $header_navigation = array();
	var $title, $file;
	/* Put the options page under theme options by default */
	var $parent = 'options-general.php';
	
	function OptionsPage($options) {
	    foreach ($options as $option) {
	    	if (!is_a($option, 'base_wp_option')) {
	    		trigger_error("Not wp_option object was passed to OptionsPage creating method", E_USER_ERROR);
	    	}
	    }
	    $this->options = $options;
	    $this->theme_name = get_current_theme();
		$this->file = basename(__FILE__);
	    $this->title = $this->theme_name . " Options";
	    $this->needed_permissions = 'edit_themes';
	}
	
	function fire_admin() {
	    if ($_SERVER['REQUEST_METHOD']=='POST') {
	    	$this->save_opts();
	    }
	    $this->show_form();
	}
	
	function show_form() {
	    include_once('form.tpl.php');
	}
	
    function save_opts() {
		foreach ($this->options as $opt) {
			if ($opt->type == 'separator') {continue;}
			$res = $opt->set_value_from_input();
			if ($res!=INVALID_VALUE) {
				$opt->save();
			} else {
				if (empty($this->tpl_vars["errors"])) {
					$this->tpl_vars["errors"] = array();
				}
				$this->tpl_vars["errors"][] = array('label'=>$opt->label, 'error'=>$opt->get_error());
			}
		}
		$this->tpl_vars['saved'] = 1;
	}
	
	function attach_to_wp() {
	    add_action('admin_menu', array($this, 'attach_to_wp_admin'));
	}
	function replace_choco_theme_icon() {
		global $current_user;
		get_currentuserinfo();
		$color_scheme = get_usermeta($current_user, 'admin_color');
		$icons_root = get_bloginfo('stylesheet_directory') . '/lib/images/';
		$this->icon = "{$icons_root}choco-icon-$color_scheme.gif";
		?>
		<script type="text/javascript" charset="utf-8">
			jQuery(function () {
			    jQuery('.toplevel_page_theme-options').find('.wp-menu-image img').attr('src', '<?php echo $this->icon ?>');
			});
			
		</script>
		<?php
	}
	function attach_to_wp_admin() {
	    add_submenu_page(
	    	$this->parent,
	    	$this->title, 
	    	$this->title, 
	    	$this->needed_permissions, 
		    $this->file,
		    array($this, 'fire_admin')
		);
		add_action('admin_footer', array($this, 'replace_choco_theme_icon'));
	}
	function add_header_navigation($nav) {
		$this->header_navigation = array_merge($this->header_navigation, $nav);
	}
}
?>