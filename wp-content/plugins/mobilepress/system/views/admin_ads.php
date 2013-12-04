<div class="wrap">
	<script src="<?php echo get_bloginfo('wpurl'); ?><?php echo MOPR_SCRIPT_PATH; ?>system/libraries/js/jquery.livequery.js" type="text/javascript" charset="utf-8"></script>
	<script>
	jQuery(document).ready(function($){
		var enabled = <?php if(isset($ads_enabled) && $ads_enabled == '1') echo "1"; else echo "0"; ?>;
		
		// Is ad serving enabled?
		if (enabled == 0)
		{
			$('div#ad_serving').hide();
		}
		else if (enabled == 1)
		{
			$('div#ad_serving').show();
		}
		
		$('select#ads_enabled').livequery('change', function(){
			enabled = $('select#ads_enabled').val();
			
			if (enabled == 0)
			{
				$('div#ad_serving').hide();
			}
			else if (enabled == 1)
			{
				$('div#ad_serving').show();
			}
		});
	});
	</script>
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Mobile Ads</h2>
	<p>MobilePress gives you the ability to integrate with <a href="http://aduity.com">Aduity</a> for mobile ad serving.</p>
	<p>You will have to <a href="http://aduity.com/register">register an account with Aduity</a>. Enter your <em>Account Public Key</em> and <em>Site Public Key</em> from your Aduity account and you will then be able to start serving ads from networks such as Admob, InMobi, Buzzcity, Quattro, Adsense and ZestAdz.</p>
	<form method="post" action="admin.php?page=mobilepress-ads">
		<h2>Aduity Account Details</h2>
		<table class="form-table">
			<tr>
				<th scope="row">Account Public Key:</th>
				<td>
					<input type="text" name="apk" class="regular-text" value="<?php if(isset($apk)) echo $apk; ?>" /> <span class="description">Find under "Get code" in Aduity.</span>
				</td>
			</tr>
			<tr>
				<th scope="row">Site Public Key:</th>
				<td>
					<input type="text" name="spk" class="regular-text" value="<?php if(isset($spk)) echo $spk; ?>" /> <span class="description">Find under "Get code" in Aduity.</span>
				</td>
			</tr>
			<tr>
				<th scope="row">Enable Ad Serving:</th>
				<td>
					<select name="ads_enabled" id="ads_enabled">
						<option value="0"<?php if (isset($ads_enabled) && ! $ads_enabled) echo " selected"; ?>>No</option>
						<option value="1"<?php if (isset($ads_enabled) && $ads_enabled) echo " selected"; ?>>Yes</option>
					</select>
				</td>
			</tr>
		</table>
		<div id="ad_serving">
		<h2>Ad Serving Options</h2>
		<table class="form-table">
			<tr>
				<th scope="row">Enable Debug Mode:</th>
				<td>
					<select name="debug_mode" id="debug_mode">
						<option value="0"<?php if (isset($debug_mode) && ! $debug_mode) echo " selected"; ?>>No</option>
						<option value="1"<?php if (isset($debug_mode) && $debug_mode) echo " selected"; ?>>Yes</option>
					</select>
					<span class="description">Useful for testing purposes. Ads will display in web browser when enabled</span>
				</td>
			</tr>
			<tr>
				<th scope="row">Ad Location:</th>
				<td>
					<select name="location">
						<option value="0"<?php if (isset($location) && $location == '0') echo " selected"; ?>>Header & Footer</option>
						<option value="1"<?php if (isset($location) && $location == '1') echo " selected"; ?>>Just Header</option>
						<option value="2"<?php if (isset($location) && $location == '2') echo " selected"; ?>>Just Footer</option>
					</select>
				</td>
			</tr>
		</table>
		</div>
		<p class="submit"><input type="submit" name="save" class="button-primary" value="Save Settings!" /></p>
	</form>
</div>