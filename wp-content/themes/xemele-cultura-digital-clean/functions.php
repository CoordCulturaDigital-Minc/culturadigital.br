<?php

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'before_widget' => '<li class="sidebox">', 
		'after_widget' => '</li>',
		'before_title' => '<h2>',
		'after_title' => '</h2>', 
	));

?>
