<?php
if ( function_exists('register_sidebar') )
register_sidebar(array('name'=>'Sidebar'
));
register_sidebar(array('name'=>'Footerbar'
));
register_sidebar(array('name'=>'SideAd',
'before_widget' => '<div id="sidead">',
'after_widget' => '</div>',
));
register_sidebar(array('name'=>'TopAd',
'before_widget' => '<div id="topad">',
'after_widget' => '</div>',
));
$GLOBALS['content_width'] = 550;
?>