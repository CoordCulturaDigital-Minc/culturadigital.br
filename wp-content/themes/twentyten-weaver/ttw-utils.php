<?php
/* This file has most of the code for handling the Main Options, plus some utility functions */

/* some helper functions to access values */
function ttw_getopt_newcolor($color) {
    global $ttw_options;
    $stdval = '';
    foreach ($ttw_options as $value) {      /* scan options array to find std color value */
	if ($value['id'] == $color) {
	    $stdval = $value['std'];
            break;
	    }
	}
    $setcolor = ttw_getopt($color);
    if ($setcolor != $stdval)
        return $setcolor;
    return false;    
}

function ttw_getopt_std($byid) {
	/* so we can see if we're using the default value */
    global $ttw_options;
    
    $lim = count($ttw_options);
    for ( $i = 0; $i < $lim; $i++ )
    {
        $value = $ttw_options[$i];
	$ttw_id = $value['id'];
	if ( $ttw_id == $byid )
	{
            return $value['std'];
        }
    }
    return false;  	
}

function t_get_font_value($byid) {
	/* get font value if not default */
	global $ttw_options;
	
	foreach ($ttw_options as $value) {
		if ($value['id'] == $byid) {
			$v = ttw_getopt($value['id']);
			if ($v == '') $v = $value['std'];
			if ($v == $value['std']) return '';
			return $v;
		}
	}
	return '';
}
	
function t_css_fontfamily($name, $fontfamily) {
	/* fill in good set of alternate fonts */
	if (stripos($fontfamily,"(serif)")) {
		$altfont = "\"Times New Roman\", Times, serif";
	} elseif (stripos($fontfamily,"(sans-serif)")) {
		$altfont = "Arial, Helvetica, sans-serif";
	} elseif (stripos($fontfamily,"(mono)")) {
		$altfont = "Courier";
	} else { $altfont = "serif";}
	
	$font = substr($fontfamily, 0 , strpos($fontfamily, " (")); /* strip out font name */
	echo ("$name { font-family: \"". $font ."\", $altfont;}\n");	
}

function t_css_color($name, $color, $altstyle='') {
        /* output a CSS color rule */
	echo("$name { color: $color; $altstyle}\n");
}
function t_css_bgcolor($name, $color, $altstyle='') {
    /* output a CSS background-color rule */
	echo("$name { background-color: $color; $altstyle}\n");
}

function ttw_put_ttw_widgetarea($area,$style) {
    // emit ttw widget area depending on various settings (for page.php and index.php)

    $showwidg = !ttw_getopt('ttw_hide_widg_pages');
    if (is_front_page() && ttw_getopt('ttw_force_widg_frontpage')) $showwidg = true;
		
    if ($showwidg && is_active_sidebar($area)) { /* add top and bottom widget areas */
	ob_start(); /* let's use output buffering to allow use of Dynamic Widgets plugin and not have empty sidebar */
	$success = dynamic_sidebar($area);
	$content = ob_get_clean();
	if ($success) {
	    ?>
	    <div id=<?php echo ('"'.$style.'"'); ?> class="widget-area" role="complementary" ><ul class="xoxo">
	    <?php echo($content) ; ?>
	    </ul></div>
	    <?php }
	}
}
define('TTW_DEBUG',false);
function ttw_debug($msg) {
    if (TTW_DEBUG) {
        echo("\n*******************>$msg<*******************<br />\n");
    }
}
?>