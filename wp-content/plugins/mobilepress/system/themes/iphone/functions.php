<?php
// Modified WP Core function for checking pagination
function mopr_check_pagination() {
	global $wp_query;
	
	$max_num_pages = $wp_query->max_num_pages;
	
	if ( $max_num_pages > 1 ) {
		return true;
	}
	else {
		return false;
	}
}

// This function checks to see if they are using permalinks
// If they are we need to display ? in the view/post comments links before the comment=true variable is declared etc
function mopr_check_permalink() {
	$permalink = get_option('permalink_structure');
	
	if ($permalink == "") {
		echo "&amp;";
	}
	else {
		echo "?";
	}
}

function CommentID() {
	global $id;
	echo $id;
}
?>
