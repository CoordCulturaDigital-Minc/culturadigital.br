<?php
function attach_main_options_page() {
	$title = "Choco Options";
	add_menu_page(
		$title,
		$title, 
		'edit_themes', 
	    basename(__FILE__),
		create_function('', '')
	);
}
add_action('admin_menu', 'attach_main_options_page');

$default_bg_color = '#3A2820';
$bg_color = wp_option::factory('color', 'background_color', 'Background Color');
$bg_color->set_default_value($default_bg_color);
$bg_color->help_text("Default color: $default_bg_color");

$colorset1 = new color_scheme('Default Scheme');
$colorset1->add_colors(array('3a2820', 'd45023', 'ffffff'));
$colorset2 = new color_scheme('Dark Scheme');
$colorset2->add_colors(array('000000', 'eab035', 'ffffff'));
$colorset3 = new color_scheme('Red Scheme');
$colorset3->add_colors(array('350505', '151515', 'ffffff'));

$color_scheme = wp_option::factory('choose_color_scheme', 'choco_color_scheme', 'Color Scheme');
$color_scheme->add_color_schemes(array($colorset1, $colorset2, $colorset3));
$color_scheme->set_default_value('Default Scheme');

$bg_image = wp_option::factory('image', 'background_image', 'Background Image');

$bg_repeat = wp_option::factory('select', 'background_repeat', 'Background Repeat');
$bg_repeat->add_options(array(
	'no-repeat'=>'No Repeat',
	'repeat-x'=>'Repeat Horizontal(repeat-x)',
	'repeat-y'=>'Repeat Vertical(repeat-y)',
	'repeat'=>'Repeat Horizontal and Vertical(repeat)',
));
$bg_repeat->set_default_value('no');

$inner_options = new OptionsPage(array(
	wp_option::factory('separator', 'theme'),
	$color_scheme,
	wp_option::factory('separator', 'background'),
	$bg_color,
	$bg_image,
	$bg_repeat,
	wp_option::factory('separator', 'scripts'),
    wp_option::factory('header_scripts', 'header_script'),
    wp_option::factory('footer_scripts', 'footer_script'),
));
$inner_options->title = 'General';
$inner_options->file = basename(__FILE__);
$inner_options->parent = "theme-options.php";
$inner_options->attach_to_wp();

function print_color_scheme_background_changer_js() {
	?>
	<script type="text/javascript" charset="utf-8">
	function rgb_to_hex(r, g, b) {
		return to_hex(r) + to_hex(g) + to_hex(b);
	}
	function to_hex(N) {
		if (N==null) 
			return "00";
		N = parseInt(N); 
		if (N==0 || isNaN(N)) 
		    return "00";
		N = Math.max(0,N);
		N = Math.min(N,255);
		N = Math.round(N);
		return "0123456789ABCDEF".charAt((N-N%16)/16) + "0123456789ABCDEF".charAt(N%16);
	}

	jQuery(function ($) {
	    $('.color-option input[type=radio]').click(function () {
	    	var color = $(this).parent().find('td:first').css('background-color');
	    	color_rgb = color.replace(')', '').replace('rgb(', '').split(',');
	    	color_hex = rgb_to_hex(color_rgb[0], color_rgb[1], color_rgb[2]);
	    	$('#background_color').val('#' + color_hex);
	    	console.log($('.color-preview'));
	    	$('.color-preview').css('background-color', '#' + color_hex);
	    });
	});
	</script>
	<?php
}
add_action('admin_footer', 'print_color_scheme_background_changer_js');
?>