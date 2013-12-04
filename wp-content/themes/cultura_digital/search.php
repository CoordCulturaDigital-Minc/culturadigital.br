<?php get_header(); ?>

<div class="boxContent1">
<div id="cse-search-results"></div>
	<script type="text/javascript">
		var googleSearchIframeName = "cse-search-results";
		var googleSearchFormName = "cse-search-box";
		var googleSearchFrameWidth = 474;
		var googleSearchDomain = "www.google.com";
		var googleSearchPath = "/cse";
	</script>
	<script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>
</div>

<?php get_sidebar(); ?>

<?php if(!is_page()) get_sidebar('slim'); ?>

<?php get_footer(); ?>
