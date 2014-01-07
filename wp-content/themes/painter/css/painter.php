<?php

	header( 'Content-type: text/css' );

	$absolute_path = explode( 'wp-content', $_SERVER[ 'SCRIPT_FILENAME' ] );

	require_once( $absolute_path[ 0 ] . 'wp-load.php' );

	$painter      = get_option( 'painter' );

	$header_image = get_header_image();

?>

body {
	color: <?php echo $painter->colors[ 'general_text' ]; ?>;
}

a {
	color: <?php echo $painter->colors[ 'general_link' ]; ?>;
}

a:visited {
	color: <?php echo $painter->colors[ 'general_link_visited' ]; ?>;
}

a:hover,
a:focus,
a:active {
	color: <?php echo $painter->colors[ 'general_link_active' ]; ?>;
}

/***************************************************************************************************
	head
***************************************************************************************************/
#head {
	text-align: <?php echo $painter->options[ 'title_align' ]; ?>;
	<?php if( !empty( $header_image ) ) : ?>
		background-image: url( <?php header_image(); ?> );
		background-repeat: no-repeat;
		background-position: center;
	<?php endif; ?>
}

#head h1 a {
	color: #<?php echo get_header_textcolor(); ?>;
}

<?php if( 'blank' == get_header_textcolor() or '' == get_header_textcolor() ) : ?>
	#head h1 a {
		top: 0px;
		left: 0px;
		position: absolute;
		width: 960px;
		height: 200px;
		display: block;
		text-indent: -9999px;
	}

	#head h2 {
		display: none;
	}

<?php endif; ?>

/***************************************************************************************************
	body
***************************************************************************************************/
#body {
	background: <?php echo $painter->colors[ 'body_background' ]; ?>;
}

/***************************************************************************************************
	navigator
***************************************************************************************************/
#navigator {
	color: <?php echo $painter->colors[ 'navigator_text' ]; ?>;
}

#navigator input,
#navigator button {
	color: <?php echo $painter->colors[ 'navigator_text' ]; ?>;
}

#navigator a {
	color: <?php echo $painter->colors[ 'navigator_link' ]; ?>;
}

#navigator a:focus,
#navigator a:hover,
#navigator a:active {
	color: <?php echo $painter->colors[ 'navigator_link_active' ]; ?>;
}

#navigator .current-menu-item a {
	color: <?php echo $painter->colors[ 'navigator_link_visited' ]; ?>;
}

#navigator .sub-menu {
	background: <?php echo $painter->colors[ 'body_background' ]; ?>;
}

/***************************************************************************************************
	content
***************************************************************************************************/
#content {
	color: <?php echo $painter->colors[ 'content_text' ]; ?>;
}

#content a {
	color: <?php echo $painter->colors[ 'content_link' ]; ?>;
}

#content a:visited {
	color: <?php echo $painter->colors[ 'content_link_visited' ]; ?>;
}

#content a:hover,
#content a:focus,
#content a:active {
	color: <?php echo $painter->colors[ 'content_link_active' ]; ?>;
}

#content .head a {
	color: <?php echo $painter->colors[ 'content_title' ]; ?>;
}

#content .head h1 {
	color: <?php echo $painter->colors[ 'content_title' ]; ?>;
	border-color: <?php echo $painter->colors[ 'content_title' ]; ?>;
}

#content .body .post_title,
#content .body .post_title a {
	color: <?php echo $painter->colors[ 'content_title' ]; ?>;
}

#content .body .post_meta,
#content .body .post_meta a {
	color: <?php echo $painter->colors[ 'content_meta' ]; ?>;
}

/***************************************************************************************************
	content: section comments
***************************************************************************************************/
#content .section_comments {
	color: <?php echo $painter->colors[ 'comment_text' ]; ?>;
}

#content .section_comments a {
	color: <?php echo $painter->colors[ 'comment_link' ]; ?>;
}

#content .section_comments a:visited {
	color: <?php echo $painter->colors[ 'comment_link_visited' ]; ?>;
}

#content .section_comments a:hover,
#content .section_comments a:focus,
#content .section_comments a:active {
	color: <?php echo $painter->colors[ 'comment_link_active' ]; ?>;
}

#content .section_comments .head a {
	color: <?php echo $painter->colors[ 'comment_title' ]; ?>;
}

#content .section_comments .head h1,
#content .section_comments .comment-reply-title {
	color: <?php echo $painter->colors[ 'comment_title' ]; ?>;
}

#content .section_comments .body .comment_author,
#content .section_comments .body .comment_author a {
	color: <?php echo $painter->colors[ 'comment_title' ]; ?>;
}

#content .section_comments .body .comment_meta,
#content .section_comments .body .comment_meta a {
	color: <?php echo $painter->colors[ 'comment_meta' ]; ?>;
}

/***************************************************************************************************
	content-split
***************************************************************************************************/
#content-split {
	color: <?php echo $painter->colors[ 'content_text' ]; ?>;
}

#content-split a {
	color: <?php echo $painter->colors[ 'content_link' ]; ?>;
}

#content-split a:visited {
	color: <?php echo $painter->colors[ 'content_link_visited' ]; ?>;
}

#content-split a:hover,
#content-split a:focus,
#content-split a:active {
	color: <?php echo $painter->colors[ 'content_link_active' ]; ?>;
}

#content-split .section .head h1 {
	color: <?php echo $painter->colors[ 'content_title' ]; ?>;
	border-color: <?php echo $painter->colors[ 'content_title' ]; ?>;
}

#content-split .post_title,
#content-split .post_title a {
	color: <?php echo $painter->colors[ 'content_title' ]; ?>;
}

#content-split .post_meta,
#content-split .post_meta a {
	color: <?php echo $painter->colors[ 'content_meta' ]; ?>;
}

/***************************************************************************************************
	widgets
***************************************************************************************************/
#sidebar,
#extras {
	color: <?php echo $painter->colors[ 'widget_text' ]; ?>;
}

#sidebar a,
#extras a {
	color: <?php echo $painter->colors[ 'widget_link' ]; ?>;
}

#sidebar a:visited,
#extras a:visited {
	color: <?php echo $painter->colors[ 'widget_link_visited' ]; ?>;
}

#sidebar a:hover,
#sidebar a:focus,
#sidebar a:active,
#extras a:hover,
#extras a:focus,
#extras a:active {
	color: <?php echo $painter->colors[ 'widget_link_active' ]; ?>;
}

#sidebar .section .head h1,
#extras .section .head h1 {
	color: <?php echo $painter->colors[ 'widget_title' ]; ?>;
	border-color: <?php echo $painter->colors[ 'widget_title' ]; ?>;
}

#sidebar .post_meta,
#sidebar .post_meta a,
#extras .post_meta,
#extras .post_meta a {
	color: <?php echo $painter->colors[ 'widget_meta' ]; ?>;
}