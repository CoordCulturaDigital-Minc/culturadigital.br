<?php
$search_term = substr($_SERVER['REQUEST_URI'],1);
$search_term = urldecode(stripslashes($search_term));
$find = array ("'.html'", "'.+/'", "'[-/_]'") ;
$replace = " " ;
$search_term = trim(preg_replace ( $find , $replace , $search_term ));
$search_term_q = preg_replace('/ /', '%20', $search_term);
$redirect_location = 'Location: '.get_home_url().'?s='.$search_term_q.'&search_404=1';
header("HTTP/1.0 404 Not Found");
header( $redirect_location );
?>