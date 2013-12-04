<?php
$navigation_type = wp_option::factory('select', 'nav_type', 'Top navigation shows');
$navigation_type->add_options(array(
	'pages'=>'Pages',
	'categories'=>'Categories',
));
$navigation_type->set_default_value('pages');

function print_nav_type_js() {
	?>
	<script type="text/javascript" charset="utf-8">
		jQuery(function ($) {
		    $('select[name=nav_type]').change(function () {
		    	$(this).parents('tr:eq(0)').nextAll('tr[class^=wp_option_choose_]').hide();
		        $(this).parents('tr:eq(0)').nextAll('tr.wp_option_choose_' + $(this).val()).show();
		        
		        if ($(this).val()=='pages') {
		        	$('.field-enable_dropdown, .field-dropdown_depth').show();
		        } else {
		        	$('.field-enable_dropdown, .field-dropdown_depth').hide();
		        }
		    }).change();
		});
	</script>
	<?php
}
add_action('admin_footer', 'print_nav_type_js');

$nav_type_pages = wp_option::factory('choose_pages', 'header_nav_pages_exclude', 'Exclude Pages');
$nav_type_pages->help_text('The "Page Order" section on the Page > Add New administration panel allows you to reorder pages');
$nav_type_pages->hide();

$nav_type_categories = wp_option::factory('choose_categories', 'header_nav_categories_exclude', 'Exclude Categories');
$nav_type_categories->hide();

$enable_dropdown = wp_option::factory('select', 'enable_dropdown', 'Enable dropdown navigation');
$enable_dropdown->add_options(array(
	'yes'=>'Yes',
	'no'=>'No',
));
$enable_dropdown->set_default_value('yes');
$dropdown_depth = wp_option::factory('select', 'dropdown_depth', 'Max dropdown levels');
$depth_range = range(1, 5);
$dropdown_depth->add_options(array_combine($depth_range, $depth_range));
$dropdown_depth->set_default_value(2);

$opt = new OptionsPage(array(
    $navigation_type,
    $nav_type_pages,
    $nav_type_categories,
    $enable_dropdown,
    $dropdown_depth,
));
$opt->title = 'Navigation';

$opt->file = basename(__FILE__);
$opt->parent = "theme-options.php";
$opt->attach_to_wp();
?>