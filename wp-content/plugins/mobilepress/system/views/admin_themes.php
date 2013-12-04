<div class="wrap">
	<div id="icon-themes" class="icon32"><br /></div>
	<h2>Mobile Themes</h2>
	<form action="admin.php" method="get">
	<table class="form-table">
		<tr>
			<td>
				<input type="hidden" name="page" value="mobilepress-themes">
				<select name="section" id="theme">
					<option value="default_theme"<?php if($section == "default_theme") { echo " selected"; } ?>>Default Browser</option>
					<option value="iphone_theme"<?php if($section == "iphone_theme") { echo " selected"; } ?>>iPhone Browser</option>
				</select>
				<input type="submit" name="select" value="Change" class="button" />
				(Select the browser you want to choose a theme for)
			</td>
		</tr>
	</table>
	</form>
	
	<?php
	// Make sure themes exist
	if ( ! empty($themes))
	{
	?>
		<h3>Current Theme For <?php echo $browser; ?></h3>
		<?php mopr_load_view('admin_themes_current_theme', array('themes' => $themes, 'current_theme' => $current_theme)); ?>
		<h3>Select A New Theme For <?php echo $browser; ?></h3>
		<?php mopr_load_view('admin_themes_available_themes', array('themes' => $themes, 'section' => $section)); ?>
	<?php
	}
	else
	{
	?>
		<p>No themes found!</p>
	<?php
	}
	?>
</div>