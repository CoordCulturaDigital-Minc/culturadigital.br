<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */

get_header();
?>

<form method="get" id="searchform" action="<?php echo get_option('home'); ?>/" >
	<p>
		<label for="s" class="accesible">Search:</label>
		<input type="text" value="" name="s" id="s" />
		<button type="submit">Go!</button>
	</p>
</form>