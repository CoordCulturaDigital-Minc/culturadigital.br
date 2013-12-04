<div class="wrap">
	<form method="post" action="admin.php?page=mobilepress">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Options</h2>
	<table class="form-table">
		<tr>
			<th scope="row">Blog Title:</th>
			<td>
				<input type="text" name="title" value="<?php echo $title; ?>" class="regular-text" /> <span class="description">Leave blank to default to your WordPress blog title</span>
			</td>
		</tr>
		<tr>
			<th scope="row">Blog Description:</th>
			<td>
				<input type="text" name="description" value="<?php echo $description; ?>" class="regular-text" /> <span class="description">Leave blank to default to your WordPress blog description</span>
			</td>
		</tr>
		<tr>
			<th scope="row">Mobile Themes Directory:</th>
			<td>
				<input type="text" name="themes_directory" value="<?php echo $themes_directory; ?>" class="regular-text" /> <span class="description">Where are you storing your mobile themes in <code>wp-content/</code>?</span>
			</td>
		</tr>
		<tr>
			<th scope="row">Force Mobile Site?</th>
			<td>
				<select name="force_mobile">
					<option value="0"<?php if ( ! $force_mobile) echo " selected"; ?>>No</option>
					<option value="1"<?php if ($force_mobile) echo " selected"; ?>>Yes</option>
				</select>
			</td>
		</tr>
	</table>
	<p class="submit"><input type="submit" name="save" class="button-primary" value="Save Changes!" /></p>
	</form>
	<h2>Did You Know?</h2>
	<p>You can easily view your mobile theme in a web browser by visiting <em><a href="<?php bloginfo('siteurl'); ?>/?mobile"><?php bloginfo('siteurl'); ?>/?mobile</a></em> or <em><a href="<?php bloginfo('siteurl'); ?>/?iphone"><?php bloginfo('siteurl'); ?>/?iphone</a></em> for the iphone theme.</p>
	<p>Remember, to view your normal blog theme again, simply visit <em><a href="<?php bloginfo('siteurl'); ?>/?nomobile"><?php bloginfo('siteurl'); ?>/?nomobile</a></em>.</p>
	<h2>Custom Themes</h2>
	<p>Why not create a custom theme for your mobile blog? Mobile themes are created in the same way as normal WordPress themes are created. Simply upload your mobile themes to <code>wp-content/<?php echo $themes_directory; ?></code></p>
	<h2>Additional Support</h2>
	<p>Check out the official <a href="http://help.aduity.com/aduity/products/aduity_mobilepress">MobilePress support docs</a> if you are having any trouble with the plugin. There is also a help desk where you can ask questions.</p>
</div>