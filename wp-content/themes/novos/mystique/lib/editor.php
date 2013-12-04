<?php /* Mystique/digitalnature */

// editor iframe style
function mystique_editor_styles($url) {
  if (!empty($url)) $url .= ',';
  $url .= trailingslashit(get_stylesheet_directory_uri()).'editor.css';
  return $url;
}

// "styles" drop down
function mystique_mcekit_editor_buttons($buttons) {
	array_unshift($buttons, 'styleselect');
	return $buttons;
}

// "styles" drop down contents
function mystique_mcekit_editor_settings($settings) {
	if (!empty($settings['theme_advanced_styles'])) $settings['theme_advanced_styles'] .= ';';	else $settings['theme_advanced_styles'] = '';

	// http://wiki.moxiecode.com/index.php/TinyMCE:Configuration/theme_advanced_styles
	$classes = array(
		__('Divider', 'mystique') => 'divider',
		__('Error text color', 'mystique') => 'error',
		__('Error box', 'mystique') => 'errorbox'
	);

	$class_settings = '';
	foreach ( $classes as $name => $value ) $class_settings .= "{$name}={$value};";

	$settings['theme_advanced_styles'] .= trim($class_settings, '; ');
	return $settings;
}

?>