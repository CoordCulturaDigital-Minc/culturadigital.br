<?php
/**
 * @package WordPress
 * @subpackage TweetPress
 */

get_header();
?>
	<div id="content" class="round">
		<div class="wrapper">
			<h2 class="center">Sorry, that page doesn't exist!</h2>
			<form method="get" action="<?php echo get_option('home'); ?>/">
				<fieldset class="common-form round">
					<label for="q">Search for articles, tags or categories</label>
					<input type="text" name="s" class="round medium" id="search_q" />
					<input type="submit" class="submit btn" value="search" id="search_submit" />
				</fieldset>
			</form>
		</div>
	</div>
<?php get_footer(); ?>