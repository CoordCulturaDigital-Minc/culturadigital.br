<?php
    // Include only functions and data used in the admin panel.

function ttw_do_admin() {
/* theme admin page */

/* This generates the startup script calls, etc, for the admin page */
    global $ttw_optionsList, $ttw_myoptionsList, $ttw_options, $ttw_adminOpts, $ttw_adminOptsDefault, $ttw_optionsListDefault;

    if (!current_user_can('edit_theme_options')) wp_die("No permission to access that page.");

    $ttw_dir =	get_bloginfo('stylesheet_directory');

     /* First, process any actions from the buttons */

    if (isset($_POST['saveoptions'])) {
        echo '<div id="message" class="updated fade"><p><strong>'.__("Twenty Ten Weaver main options saved.",TTW_TRANS).'</strong></p></div>';

	foreach ($ttw_options as $value) {			/* only reset main options so use $ttw_options */
	    $id = $value['id'];
            if ($value['type'] == "admincheckbox") {            // special case for a few admin options
                if (isset($_POST[$id]))
                        ttw_setadminopt($id, $_POST[$id]);
                else
                        ttw_deleteadminopt($id);
            } else {
                if( isset( $_POST[ $id ] ) ) {
                    $v = $_POST[$id];
                    ttw_setopt( $id, $v );
                } else {
                    ttw_defaultopt( $id );
                }
            }
	}
	ttw_saveopts();
    }

    if (isset($_POST['saveadvanced'])) {
        echo '<div id="message" class="updated fade"><p><strong>'.__("Twenty Ten Weaver advanced options saved.",TTW_TRANS).'</strong></p></div>';

        // THEME OPTS - advanced  theme opts

        ttw_post_opt('ttw_head_opts');
        ttw_post_opt('ttw_theme_head_opts');

        ttw_post_opt('ttw_footer_opts');
        ttw_post_opt('ttw_header_insert');
        ttw_post_opt('ttw_header_frontpage_only');

        // ADMIN OPTS - per site tags - not theme related

        ttw_post_adminopt('ttw_hide_preview');
        ttw_post_adminopt('ttw_hide_theme_thumbs');
        ttw_post_adminopt('ttw_hide_metainfo');
        ttw_post_adminopt('ttw_metainfo');
        ttw_post_adminopt('ttw_end_opts');
	ttw_post_adminopt('ttw_copyright');
	ttw_post_adminopt('ttw_hide_poweredby');

        // NOW, save everything
	ttw_saveopts();
    }

    if (isset($_POST['setsubtheme']) || isset($_POST['setsubtheme2'])) {
	/* seems like Mozilla doesn't like 2 sets of select inputs on same page, so we make up 2 ids/names to use */

	if (isset($_POST['setsubtheme'])) $pID = 'ttw_subtheme';
	else $pID = 'ttw_subtheme2';

        $cur_subtheme = $_POST[ $pID];	/* must have been set to get here */
        if ($cur_subtheme == '') $cur_subtheme = TTW_DEFAULT_THEME;	/* but just in case */

        /* now, i set all values for theme */
        st_set_subtheme($cur_subtheme);

        $t = ttw_getopt('ttw_subtheme'); if ($t == '') $t = TTW_DEFAULT_THEME;    /* did we save a theme? */
        echo '<div id="message" class="updated fade"><p><strong>'.__("Twenty Ten Weaver options reset to sub-theme: ",TTW_TRANS).$t.
	'.</strong></p></div>';
    }

    if (isset($_POST['changethemename'])) {

	if (isset($_POST['newthemename'])) {
            $new_name = sanitize_user($_POST['newthemename']);
            ttw_setopt('ttw_themename',$new_name);
            echo '<div id="message" class="updated fade"><p><strong>Theme name changed to '.$new_name.'</strong></p></div>';
        }
    }

    if (isset($_POST['savemytheme'])) {
	ttw_savemytheme();
	echo '<div id="message" class="updated fade"><p><strong>'.__('All current main and advanced options saved in <em>My Saved Theme</em>.',TTW_TRANS).'</strong></p></div>';
    }

    if (isset($_POST['reset_weaver'])) {
	// delete everything!
	echo '<div id="message" class="updated fade"><p><strong>All Weaver settings have been reset to the default.</strong></p></div>';
	delete_option('ttw_options');
	delete_option('ttw_myoptions');
	delete_option('ttw_adminoptions');

	$ttw_optionsList = $ttw_optionsListDefault;
	foreach ($ttw_options as $value ) {
	    ttw_defaultopt( $value['id'] );
	}

	$ttw_myoptionsList = $ttw_optionsList;
	$ttw_adminOpts = $ttw_adminOptsDefault;
	ttw_saveopts();
	st_set_subtheme(TTW_START_THEME);
    }

    if (isset($_POST['filesavetheme'])) {
        $base = strtolower(sanitize_file_name($_POST['savethemename']));
        $temp_url =  ttw_write_current_theme($base);
        if ($temp_url == '')
            echo '<div id="message" class="updated fade"><p><strong>Invalid name supplied to save theme to file.</strong></p></div>';
        else
            echo '<div id="message" class="updated fade"><p><strong>'.__("All current main and advanced options saved in $temp_url.",TTW_TRANS).'</strong></p></div>';
   }

    if (isset($_POST['uploadit']) &&  $_POST['uploadit'] == 'yes') { // don't know if need 2nd test or not...
        ttw_uploadit();
    }

    if (isset($_POST['uploadthemeurl'])) {
        // url method
        $filename = esc_url($_POST['ttw_uploadname'] );
	if (ttw_upload_theme($filename)) {
	    $t = ttw_getopt('ttw_subtheme'); if ($t == '') $t = TTW_DEFAULT_THEME;    /* did we save a theme? */
	    echo '<div id="message" class="updated fade"><p><strong>'.__("Twenty Ten Weaver theme options reset to uploaded theme, saved as: ",TTW_TRANS).$t.
		'.</strong></p></div>';
	} else {
	    echo ('<div id="message" class="updated fade"><p><strong><em style="color:red;">'.
		__('INVALID THEME URL PROVIDED - Try Again',TTW_TRANS).'</em></strong></p></div>');
	}
    }

    if (isset($_POST['restoretheme'])) {
        $wpdir = wp_upload_dir();
        $fn = $wpdir['basedir'].'/weaver-subthemes/'.$_POST['ttw_restorename'];

	if (ttw_upload_theme($fn )) {
	    $t = ttw_getopt('ttw_subtheme'); if ($t == '') $t = TTW_DEFAULT_THEME;    /* did we save a theme? */
	    echo '<div id="message" class="updated fade"><p><strong>'.__("Twenty Ten Weaver theme restored from file, saved as: ",TTW_TRANS).$t.
		'.</strong></p></div>';
	} else {
	    echo ('<div id="message" class="updated fade"><p><strong><em style="color:red;">'.
		__('INVALID FILE NAME PROVIDED - Try Again',TTW_TRANS).'</em></strong></p></div>');
	}
    }

    if (isset($_POST['deletetheme'])) {
        $myFile = $_POST['selectName'];
        if ($myFile != "None") {
            $wpdir = wp_upload_dir();
            unlink($wpdir['basedir'].'/weaver-subthemes/'.$myFile);
	    echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="updated fade"><p>File: <strong>'.$myFile.'</strong> has been deleted.</p></div>';
        }
    }

    if (isset($_POST['ttw_save_extension'])) {			/* for theme extensions */
	do_action('ttwx_save_extension');
    }
?>
<h3><?php echo(TTW_THEMEVERSION); ?> Options</h3>
<div id="tabwrap">
  <div id="tab-container-1" class='yetii'>
    <ul id="tab-container-1-nav" class='yetii'>
	<li><a href="#tab0"><?php echo(__('Weaver Themes',TTW_TRANS)); ?></a></li>
	<li><a href="#tab1"><?php echo(__('Main Options',TTW_TRANS)); ?></a></li>
<?php	if (!is_multisite() || current_user_can('install-themes') || TTW_MULTISITE_ALLOPTIONS) { ?>
	<li><a href="#tab2"><?php echo(__('Advanced Options',TTW_TRANS)); ?></a></li>
	<li><a href="#tab3"><?php echo(__('Save/Restore Themes',TTW_TRANS)); ?></a></li>
	<li><a href="#tab4"><?php echo(__('Snippets',TTW_TRANS)); ?></a></li>
<?php	} ?>
	<li><a href="#tab5"><?php echo(__('Help',TTW_TRANS)); ?></a></li>
	<?php do_action('ttwx_add_extended_tab_title','<li><a href="#tab6">','</a></li>'); ?>
    </ul>

    <div id="tab0" class="tab" >
         <?php ttw_themes_admin(); ?>
    </div>
    <div id="tab1" class="tab" >
         <?php ttw_options_admin(); ?>
    </div>
<?php if (!is_multisite() || current_user_can('install-themes') || TTW_MULTISITE_ALLOPTIONS) { ?>
    <div id="tab2" class="tab">
       <?php ttw_advanced_admin(); ?>
    </div>
    <div id="tab3" class="tab">
       <?php ttw_saverestore_admin(); ?>
    </div>
    <div id="tab4" class="tab">
       <?php ttw_snippets_admin(); ?>
    </div>
<?php } ?>
    <div id="tab5" class="tab">
       <?php ttw_help_admin(); ?>
    </div>
    <?php do_action('ttwx_add_extended_tab','<div id="tab6" class="tab" >', '</div>'); /* extended option admin tab */ ?>

  </div>

<?php if (!ttw_getadminopt('ttw_hide_preview')) { ?>

<h3>Preview of site. Displays current look <em>after</em> you save options or select sub-theme.</h3>
<iframe id="preview" name="preview" src="<?php echo get_option('siteurl');  ?>?temppreview=true" style="width:100%;height:400px;border:1px solid #ccc"></iframe>
<?php } else { echo("<h3>Site Preview Disabled</h3>\n"); } ?>
</div>
    <script type="text/javascript">
	var tabber1 = new Yetii({
	id: 'tab-container-1',
	persist: true
	});
</script>
<?php
}	/* end ttw_do_admin */

function ttw_uploadit() {
    // upload theme from users computer
           // they've supplied and uploaded a file

	$ok = true;     // no errors so far

        if (isset($_FILES['uploaded']['name']))
            $filename = $_FILES['uploaded']['name'];
        else
            $filename = "";

        if (isset($_FILES['uploaded']['tmp_name'])) {
            $openname = $_FILES['uploaded']['tmp_name'];
        } else {
            $openname = "";
        }

	//Check the file extension
	$check_file = strtolower($filename);
	$ext_check = end(explode('.', $check_file));

	if ($filename == "") {
	    $errors[] = "You didn't select a file to upload<br />";
	    $ok = false;
	}

	if ($ok && $ext_check != 'wvr'){
	    $errors[] = "Theme files must have <em>.wvr</em> extension.<br />";
	    $ok = false;
	}

        if ($ok) {
            $handle = fopen($openname,'r');     // now try to open the uploaded file
            if (!$handle) {
                $errors[] = '<strong><em style="color:red;">'.
                 __('Sorry, there was a problem uploading your file. You may need to check your folder permissions or other server settings.',TTW_TRANS).'</em></strong>'.
                    "<br />(Trying to use file '$openname')";
                $ok = false;
            }
        }

	if (!$ok) {
	    echo '<div id="message" class="updated fade"><p><strong><em style="color:red;">ERROR</em></strong></p><p>';
	    foreach($errors as $error){
		echo $error.'<br />';
	    }
	    echo '</p></div>';
	} else {    // OK - read file and save to My Saved Theme
            // $handle has file handle to temp file.
            $contents = null;
            while ( !feof($handle) ) {
                $contents .= fread($handle, 1024);
            }
            fclose($handle);

            if (!ttw_save_serialized_theme($contents)) {
                echo '<div id="message" class="updated fade"><p><strong><em style="color:red;">'.
                __('Sorry, there was a problem uploading your file. The file you picked was not a valid Weaver theme file.',TTW_TRANS).'</em></strong></p></div>';
	    } else {
                $t = ttw_getopt('ttw_subtheme'); if ($t == '') $t = TTW_DEFAULT_THEME;    /* did we save a theme? */
                echo '<div id="message" class="updated fade"><p><strong>'.__("Twenty Ten Weaver theme options reset to uploaded theme, saved as: ",TTW_TRANS).$t.
                    '.</strong></p></div>';
            }
        }
}

function ttw_post_adminopt($optname) {
    if (isset($_POST[$optname]))
	ttw_setadminopt($optname, $_POST[$optname]);
    else
	ttw_deleteadminopt($optname);
}

function ttw_post_opt($optname) {
    if (isset($_POST[$optname]))
	ttw_setopt($optname, $_POST[$optname]);
    else
	ttw_deleteopt($optname);
}

function ttw_options_admin() {
/* theme admin page - Main Options tab */
	global $ttw_options;
        ?>
<h3>Main Options</h3>
<p><strong>Main color and appearance options</strong></p>
<?php
	mytheme_put_form($ttw_options, "saveoptions","Save Current Settings", true);
        echo "</br><small>Note: color value boxes also allow text such as <em>blue, inherit , transparent,</em> etc. The values are not checked if they are valid color attributes.</small>";
}

function mytheme_put_form($ttw_options_list, $actname, $flabel, $showFirstInput) {
    /* output a list of options - this really does the layout for the options defined in an array */

    echo '<form method="post">  <table class="optiontable">' ."\n";

    if ($showFirstInput) {          /* maybe show extra submit button at top */
	// Don't change this - IE8 an IE9 have troubles if not done this way.
        // echo("<span class='submit'><input name='$actname' type='submit' value='$flabel' class = 'button-primary' /></span><br />");
	echo("<input name='$actname' type='submit' value='$flabel' class = 'button-primary' /><br /><br />");

    }

    foreach ($ttw_options_list as $value) {
	if ($value['type'] == "text") { ?>
		<tr>
		<th scope="row" align="right"><?php echo $value['name']; ?>:&nbsp;</th>
		<td>
		<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" class="regular-text" value="<?php if ( ttw_getopt( $value['id'] ) != "") { echo ttw_getopt( $value['id'] ); } else { echo $value['std']; } ?>" />
		</td>
		<?php if ($value['info'] != '') {
		    echo('<td style="padding-left: 10px"><small>'); echo $value['info']; echo("</small></td>");
		} ?>
		</tr>
	<?php } elseif ($value['type'] == "text-int") { /* NOT WORKING - need to make work to verify int input */ ?>
                <tr>
		<th scope="row" align="right"><?php echo $value['name']; ?>:&nbsp;</th>
		<td>
		<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" onKeyup="validInt(this.value)"
			value="<?php if ( ttw_getopt( $value['id'] ) != "") { echo ttw_getopt( $value['id'] ); } else { echo $value['std']; } ?>" />
		</td>
		<?php if ($value['info'] != '') {
		    echo('<td style="padding-left: 10px"><small>'); echo $value['info']; echo("</small></td>");
		} ?>
		</tr>
	<?php } elseif ($value['type'] == "ctext") {
                $pclass = 'color {hash:true, adjust:false}';    // starting with V 1.3, allow text in color pickers
        ?>
		<tr>
		<th scope="row" align="right"><?php echo $value['name']; ?>:&nbsp;</th>
		<td>
		<input class="<?php echo $pclass; ?>" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if ( ttw_getopt( $value['id'] ) != "") { echo ttw_getopt( $value['id'] ); } else { echo $value['std']; } ?>" />
		</td>
		<?php if ($value['info'] != '') {
		    echo('<td style="padding-left: 10px"><small>'); echo $value['info']; echo("</small></td>");
		} ?>
	<?php } elseif ($value['type'] == "checkbox") { ?>
		<tr>
		<th scope="row" align="right"><?php echo $value['name']; ?>:&nbsp;</th>
		<td>
		<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php echo (ttw_getopt( $value['id'] ) ? "checked" : ""); ?> >
		</td>
		<?php if ($value['info'] != '') {
		    echo('<td style="padding-left: 10px"><small>'); echo $value['info']; echo("</small></td>");
		} ?>
        <?php } elseif ($value['type'] == "admincheckbox") { ?>
		<tr>
		<th scope="row" align="right"><?php echo $value['name']; ?>:&nbsp;</th>
		<td>
		<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php echo (ttw_getadminopt( $value['id'] ) ? "checked" : ""); ?> >
		</td>
		<?php if ($value['info'] != '') {
		    echo('<td style="padding-left: 10px"><small>'); echo $value['info']; echo("</small></td>");
		} ?>
	<?php } elseif ($value['type'] == "select") { ?>
		<tr>
		<th scope="row" align="right"><?php echo $value['name']; ?>:&nbsp;</th>
		<td>
		<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                <?php foreach ($value['value'] as $option) { ?>
                <option<?php if ( ttw_getopt( $value['id'] ) == $option) { echo ' selected="selected"'; }?>><?php echo $option; ?></option>
                <?php } ?>
		</select>
		</td>
		</td>
		<?php if ($value['info'] != '') {
		    echo('<td style="padding-left: 10px"><small>'); echo $value['info']; echo("</small></td>");
		} ?>
		</tr>
        <?php } elseif ($value['type'] == "imgselect") {
                /* special handling of bullet images - will add the bullet image to each item */ ?>
		<tr>
		<th scope="row" align="right"><?php echo $value['name']; ?>:&nbsp;</th>
		<td>
		<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                <?php

                foreach ($value['value'] as $opt) {
                    $img = get_bloginfo('stylesheet_directory') . '/images/bullets/' . $opt . '.gif';
                    if ($opt == '')     /* special case - the empty default option */
                        $style = '';
                    else
                        $style = ' style="background-image:url('. $img . ');background-repeat:no-repeat;padding-left:16px;height:16px;line-height:16px;"';

                    if (ttw_getopt( $value['id'] ) == $opt)
                        $sel = ' selected="selected" ';
                    else
                        $sel = '';
                    printf('<option%s%s>%s</option>',$sel,$style,$opt); echo("\n");
                }
                ?>
                </select>
		</td>
		</td>
		<?php if ($value['info'] != '') {
		    echo('<td style="padding-left: 10px"><small>'); echo $value['info']; echo("</small></td>");
		} ?>
		</tr>
	<?php } elseif ($value['type'] == "header") { ?>
		<tr>
		<th scope="row" align="left"><?php echo '<span style="color:blue;"><em><u>'.$value['name'].'</u></em></span>'; ?></th>
		<?php if ($value['info'] != '') {
		    echo('<td>&nbsp;</td><td style="padding-left: 10px"><small><em><u>'); echo $value['info']; echo("</u></em></small></td>");
		} ?>
		</tr>
	<?php }
	} ?>
 </table>
	<br />
	<input name="<?php echo($actname); ?>" type="submit" value="<?php echo($flabel); ?>" class = "button-primary" />
	<input type="hidden" name="action" value="<?php echo($actname); ?>" />
	<br /><br />
</form>
<?php
}

// now that we are in the admin code, we can load the rest of the stuff needed
require_once('ttw-subthemes.php');
require_once('ttw-advancedopts.php');
require_once('ttw-help.php');
?>
