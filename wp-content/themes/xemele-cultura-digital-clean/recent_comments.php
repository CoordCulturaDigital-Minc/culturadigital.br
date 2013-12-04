<?php
	if ('functions.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('<h1 style="color:red;">Please do not load this page directly.</h1>');




////////////////////////////////////////////////////////////////////////////////
// Recent Comments
////////////////////////////////////////////////////////////////////////////////
function mw_recent_comments(
	$no_comments = 10, 
	$show_pass_post = false, 
	$title_length = 100, 	// shortens the title if it is longer than this number of chars
	$author_length = 30,	// shortens the author if it is longer than this number of chars
	$wordwrap_length = 50, // adds a blank if word is longer than this number of chars
	$type = 'all', 	// Comments, trackbacks, or both?
	$format = '<li>%date%: <a href="%permalink%" title="%title%">%title%</a> (via %author_full%)</li>',
	$date_format = 'd.m.y, H:i',
	$none_found = '<li>Keine Kommentare vorhanden.</li>',	// None found
	$type_text_pingback = 'Pingback via',
	$type_text_trackback = 'Trackback via',
	$type_text_comment = 'via'

	) {

	//Language...
	$mwlang_anonymous = 'Anonym'; // Anonymous
	$mwlang_authorurl_title_before = 'Webseite von &lsaquo;';
	$mwlang_authorurl_title_after = '&rsaquo; besuchen';


    global $wpdb;

    $request = "SELECT ID, comment_ID, comment_content, comment_author, comment_author_url, comment_date, post_title, comment_type
				FROM $wpdb->comments LEFT JOIN $wpdb->posts ON $wpdb->posts.ID=$wpdb->comments.comment_post_ID
				WHERE post_status IN ('publish','static')";
 
	switch($type) {
		case 'all':
			// add nothing
			break;
		case 'comment_only':
			// 
			$request .= "AND $wpdb->comments.comment_type='' ";
			break;
		case 'trackback_only':
			$request .= "AND ( $wpdb->comments.comment_type='trackback' OR $wpdb->comments.comment_type='pingback' ) ";
			break;
	 default:
 		//
			break;
 	
	}
 	
	if (!$show_pass_post) $request .= "AND post_password ='' ";
	
	$request .= "AND comment_approved = '1' ORDER BY comment_ID DESC LIMIT $no_comments";
	
	$comments = $wpdb->get_results($request);
    $output = '';
	if ($comments) {
    	foreach ($comments as $comment) {

			// Permalink to post/comment
			$loop_res['permalink'] = get_permalink($comment->ID). '#comment-' . $comment->comment_ID;

			// Title of the post
			$loop_res['post_title'] = stripslashes($comment->post_title);
			$loop_res['post_title'] = wordwrap($loop_res['post_title'], $wordwrap_length, ' ' , 1);
			
			if (strlen($loop_res['post_title']) >= $title_length) { 
				$loop_res['post_title'] = substr($loop_res['post_title'], 0, $title_length) . '&#8230;';
			}

			// Author's name only
        	$loop_res['author_name'] = stripslashes($comment->comment_author);
			$loop_res['author_name'] = wordwrap($loop_res['author_name'], $wordwrap_length, ' ' , 1);

			if ($loop_res['author_name'] == '') $loop_res['author_name'] = $mwlang_anonymous; 
			if (strlen($loop_res['author_name']) >= $author_length) { 
				$loop_res['author_name'] = substr($loop_res['author_name'], 0, $author_length) . '&#8230;';
			}
			
			// Full author (link, name)
			$author_url = $comment->comment_author_url;
			if (empty($author_url)) {
				$loop_res['author_full'] = $loop_res['author_name'];
			} else {
				$loop_res['author_full'] = '<a href="' . $author_url . '" title="' . $mwlang_authorurl_title_before . $loop_res['author_name'] . $mwlang_authorurl_title_after . '">' . $loop_res['author_name'] . '</a>';
			}

/*
			// Comment excerpt	
			$comment_excerpt = strip_tags($comment->comment_content);
			$comment_excerpt = stripslashes($comment_excerpt);
			if (strlen($comment_excerpt) >= $comment_length) { 
				$comment_excerpt = substr($comment_excerpt, 0, $comment_length) . '...';
			}
*/

			// Comment type
			if ( $comment->comment_type == 'pingback' ) {
				$loop_res['comment_type'] = $type_text_pingback;
			} elseif ( $comment->comment_type == 'trackback' ) {
				$loop_res['comment_type'] = $type_text_trackback;
			} else {
				$loop_res['comment_type'] = $type_text_comment;
			}
			
			// Date of comment
			$loop_res['comment_date'] = mysql2date($date_format, $comment->comment_date);

			// Output element
			$element_loop = str_replace('%permalink%', $loop_res['permalink'], $format);
			$element_loop = str_replace('%title%', $loop_res['post_title'], $element_loop);
			$element_loop = str_replace('%author_name%', $loop_res['author_name'], $element_loop);
			$element_loop = str_replace('%author_full%', $loop_res['author_full'], $element_loop);
			$element_loop = str_replace('%date%', $loop_res['comment_date'], $element_loop);
			$element_loop = str_replace('%type%', $loop_res['comment_type'], $element_loop);
						
			$output .= $element_loop . "\n";


		} //foreach
		
		$output = convert_smilies($output);
        
	} else {
		$output .= $none_found;
    }

    echo $output;
}

?>
