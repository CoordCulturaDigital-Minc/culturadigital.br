<div class="clear"></div>
<table id="availablethemes" cellspacing="0" cellpadding="0">
<?php
$style = '';

$theme_names = array_keys($themes);
natcasesort($theme_names);

$table = array();
$rows = ceil(count($theme_names) / 3);
for ( $row = 1; $row <= $rows; $row++ )
	for ( $col = 1; $col <= 3; $col++ )
		$table[$row][$col] = array_shift($theme_names);

foreach ( $table as $row => $cols ) {
?>
<tr>
<?php
foreach ( $cols as $col => $theme_name ) {
	$class = array('available-theme');
	if ( $row == 1 ) $class[] = 'top';
	if ( $col == 1 ) $class[] = 'left';
	if ( $row == $rows ) $class[] = 'bottom';
	if ( $col == 3 ) $class[] = 'right';
?>
	<td class="<?php echo join(' ', $class); ?>">
<?php if ( !empty($theme_name) ) :
	$template = $themes[$theme_name]['Template'];
	$stylesheet = $themes[$theme_name]['Stylesheet'];
	$title = $themes[$theme_name]['Title'];
	$version = $themes[$theme_name]['Version'];
	$description = $themes[$theme_name]['Description'];
	$author = $themes[$theme_name]['Author'];
	$screenshot = $themes[$theme_name]['Screenshot'];
	$stylesheet_dir = $themes[$theme_name]['Stylesheet Dir'];
	$template_dir = $themes[$theme_name]['Template Dir'];
	$parent_theme = $themes[$theme_name]['Parent Theme'];
	$theme_root_uri = $themes[$theme_name]['Theme Root URI'];
	$theme_root = $themes[$theme_name]['Theme Root'];
	$tags = $themes[$theme_name]['Tags'];
	$activate_link = "admin.php?page=mobilepress-themes&amp;section=".urlencode($section)."&amp;action=activate&amp;template=".urlencode($template)."&amp;theme=".urlencode($title);
	$activate_text = esc_attr( sprintf( __('Activate &#8220;%s&#8221;'), $title ) );
	$actions = array();
	$actions[] = '<a href="' . $activate_link .  '" class="activatelink" title="' . $activate_text . '">' . __('Activate') . '</a>';
	$actions = apply_filters('theme_action_links', $actions, $themes[$theme_name]);
	$actions = implode ( ' | ', $actions );
?>
		<a href="<?php echo $activate_link; ?>" class="theme screenshot">
<?php if ( $screenshot ) : ?>
			<img src="<?php echo $theme_root_uri . '/' . $stylesheet . '/' . $screenshot; ?>" alt="" />
<?php endif; ?>
		</a>
<h3><?php
	/* translators: 1: theme title, 2: theme version, 3: theme author */
	printf(__('%1$s %2$s by %3$s'), $title, $version, $author) ; ?></h3>
<p class="description"><?php echo $description; ?></p>
<span class='action-links'><?php echo $actions ?></span>
<?php if ($parent_theme) {
	/* translators: 1: theme title, 2:  template dir, 3: stylesheet_dir, 4: theme title, 5: parent_theme */ ?>
	<p><?php printf(__('The template files are located in <code>%2$s</code>.  The stylesheet files are located in <code>%3$s</code>.  <strong>%4$s</strong> uses templates from <strong>%5$s</strong>.  Changes made to the templates will affect both themes.'), $title, str_replace( WP_CONTENT_DIR, '', $template_dir ), str_replace( WP_CONTENT_DIR, '', $stylesheet_dir ), $title, $parent_theme); ?></p>
<?php } else { ?>
	<p><?php printf(__('All of this theme&#8217;s files are located in <code>%2$s</code>.'), $title, str_replace( WP_CONTENT_DIR, '', $template_dir ), str_replace( WP_CONTENT_DIR, '', $stylesheet_dir ) ); ?></p>
<?php } ?>
<?php if ( $tags ) : ?>
<p><?php _e('Tags:'); ?> <?php echo join(', ', $tags); ?></p>
<?php endif; ?>
<?php endif; // end if not empty theme_name ?>
	</td>
<?php } // end foreach $cols ?>
</tr>
<?php } // end foreach $table ?>
</table>
<br class="clear" />