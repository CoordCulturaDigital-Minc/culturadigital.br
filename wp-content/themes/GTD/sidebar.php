<div id="sidebar">



<?php include (TEMPLATEPATH . "/searchform.php"); ?>


<ul>

<lu>
<h2>The Team</h2>
<ul>
<?php wp_list_authors('show_fullname=1&optioncount=1&exclude_admin=0'); ?>
</ul></li>





<?php 

if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) {

	echo prologue_widget_recent_comments_avatar(array('before_widget' => ' <li id="recent-comments" class="widget widget_recent_comments"> ', 'after_widget' => '</li>', 'before_title' =>'<h2>', 'after_title' => '</h2>'  ));



	$before = '<li><h2>'.__('Recent Tags', 'p2')."</h2>\n";

	$after = "</li>\n";

	$num_to_show = 35;

	echo prologue_recent_projects( $num_to_show, $before, $after );

} // if dynamic_sidebar

?>



	</ul>

<div style="clear: both;"></div>

</div> <!-- // sidebar -->