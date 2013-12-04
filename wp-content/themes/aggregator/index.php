<?php get_header(); ?>
<script language="javascript"> 
function showmore_content(divid)
{
	if(document.getElementById(divid).className == 'nb-list')
	{
		document.getElementById(divid).className = 'nb-list nb-list1';
	}else
	{
		document.getElementById(divid).className = 'nb-list';
	}
}
</script>
<style>
.nb-list div
{
	display:none;
}
.more
{
	display:block !important;
}
.nb-list1 div
{
display:block !important;
height:auto !important;}
</style>
<div id="wrapper" class="clearfix">
<ul class="single_column ">
<?php  dynamic_sidebar(1); ?>
</ul>
</div>
<!-- wrapper #end -->

<!-- footer #end -->
<script src="<?php bloginfo('template_directory'); ?>/scripts/newsblocks.js" type="text/javascript" charset="utf-8"></script>
<?php get_footer(); ?>
