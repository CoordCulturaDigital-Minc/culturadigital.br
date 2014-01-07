<?php

/**
 * ngg_convert_tags() - Import the tags into the wp tables (only required for pre V1.00 versions)
 *
 * @return Success Message
 */
function ngg_convert_tags() {
	global $wpdb, $wp_taxonomies;

	// get the obsolete tables
	$wpdb->nggtags						= $wpdb->prefix . 'ngg_tags';
	$wpdb->nggpic2tags					= $wpdb->prefix . 'ngg_pic2tags';

	$picturelist = $wpdb->get_col("SELECT pid FROM $wpdb->nggpictures");
	if ( is_array($picturelist) ) {
		foreach($picturelist as $id) {
			$tags = array();
			$tagarray = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggpic2tags AS t INNER JOIN $wpdb->nggtags AS tt ON t.tagid = tt.id WHERE t.picid = '$id' ORDER BY tt.slug ASC ");
			if (!empty($tagarray)){
				foreach($tagarray as $element) {
					$tags[$element->id] = $element->name;
				}
				wp_set_object_terms($id, $tags, 'ngg_tag');
			}
		}
	}
}

/**
 * ngg_convert_filestructure() - converter for old thumnail folder structure
 *
 * @return void
 */
function ngg_convert_filestructure() {
	global $wpdb;

	$gallerylist = $wpdb->get_results("SELECT * FROM $wpdb->nggallery ORDER BY gid ASC", OBJECT_K);
	if ( is_array($gallerylist) ) {
		$errors = array();
		foreach($gallerylist as $gallery) {
			$gallerypath = WINABSPATH.$gallery->path;

			// old mygallery check, convert the wrong folder/ file name now
			if (@is_dir($gallerypath . '/tumbs')) {
				if ( !@rename($gallerypath . '/tumbs' , $gallerypath .'/thumbs') )
					$errors[] = $gallery->path . '/thumbs';
				// read list of images
				$imageslist = nggAdmin::scandir($gallerypath . '/thumbs');
				if ( !empty($imageslist)) {
					foreach($imageslist as $image) {
						$purename = substr($image, 4);
						if ( !@rename($gallerypath . '/thumbs/' . $image, $gallerypath . '/thumbs/thumbs_' . $purename ))
							$errors[] = $gallery->path . '/thumbs/thumbs_' . $purename ;
					}
				}
			}
		}

		if (!empty($errors)) {
			echo "<div class='error_inline'><p>". __('Some folders/files could not renamed, please recheck the permission and rescan the folder in the manage gallery section.', 'nggallery') ."</p>";
			foreach($errors as $value) {
				echo __('Rename failed', 'nggallery') . ' : <strong>' . $value . "</strong><br />\n";
			}
			echo '</div>';
		}
	}
}

/**
 * Move the imagerotator outside the plugin folder, as we remove it from the REPO with the next update
 *
 * @return string $path URL to the imagerotator
 */
function ngg_move_imagerotator() {

	$upload = wp_upload_dir();

	// look first at the old place and move it
	if ( file_exists( NGGALLERY_ABSPATH . 'imagerotator.swf' ) )
		@rename(NGGALLERY_ABSPATH . 'imagerotator.swf', $upload['basedir'] . '/imagerotator.swf');

	// If it's successful then we return the new path
	if ( file_exists( $upload['basedir'] . '/imagerotator.swf' ) )
		return $upload['baseurl'] . '/imagerotator.swf';

	//In some worse case it's still at the old place
	if ( file_exists( NGGALLERY_ABSPATH . 'imagerotator.swf' ) )
		return NGGALLERY_URLPATH . 'imagerotator.swf';

	// if something failed, we must return a empty string
	return '';
}

/**
 * ngg_import_date_time() - Read the timestamp from exif and insert it into the database
 *
 * @return void
 */
function ngg_import_date_time() {
	global $wpdb;

	$imagelist = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid ORDER BY tt.pid ASC");
	if ( is_array($imagelist) ) {
		foreach ($imagelist as $image) {
			$picture = new nggImage($image);
			$meta = new nggMeta($picture->pid, true);
			$date = $meta->get_date_time();
			$wpdb->query("UPDATE $wpdb->nggpictures SET imagedate = '$date' WHERE pid = '$picture->pid'");
		}
	}
}

/**
 * Adding a new column if needed
 * Example : ngg_maybe_add_column( $wpdb->nggpictures, 'imagedate', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER alttext");
 *
 * @param string $table_name Database table name.
 * @param string $column_name Database column name to create.
 * @param string $create_ddl SQL statement to create column
 * @return bool True, when done with execution.
 */
function ngg_maybe_add_column($table_name, $column_name, $create_ddl) {
	global $wpdb;

	foreach ($wpdb->get_col("SHOW COLUMNS FROM $table_name") as $column ) {
		if ($column == $column_name)
			return true;
	}

	//didn't find it try to create it.
	$wpdb->query("ALTER TABLE $table_name ADD $column_name " . $create_ddl);

	// we cannot directly tell that whether this succeeded!
	foreach ($wpdb->get_col("SHOW COLUMNS FROM $table_name") as $column ) {
		if ($column == $column_name)
			return true;
	}

	echo("Could not add column $column_name in table $table_name<br />\n");
	return false;
}

/**
 * nggallery_upgrade_page() - This page showsup , when the database version doesn't fir to the script NGG_DBVERSION constant.
 *
 * @return Upgrade Message
 */
function nggallery_upgrade_page()  {

	$filepath    = admin_url() . 'admin.php?page=' . $_GET['page'];

	if ( isset($_GET['upgrade']) && $_GET['upgrade'] == 'now') {
		nggallery_start_upgrade($filepath);
		return;
	}
?>
<div class="wrap">
	<h2><?php _e('Upgrade NextGEN Gallery', 'nggallery') ;?></h2>
	<p><?php _e('The script detect that you upgrade from a older version.', 'nggallery') ;?>
	   <?php _e('Your database tables for NextGEN Gallery is out-of-date, and must be upgraded before you can continue.', 'nggallery'); ?>
       <?php _e('If you would like to downgrade later, please make first a complete backup of your database and the images.', 'nggallery') ;?></p>
	<p><?php _e('The upgrade process may take a while, so please be patient.', 'nggallery'); ?></p>
	<h3><a href="<?php echo $filepath;?>&amp;upgrade=now"><?php _e('Start upgrade now', 'nggallery'); ?>...</a></h3>
</div>
<?php
}

/**
 * nggallery_start_upgrade() - Proceed the upgrade routine
 *
 * @param mixed $filepath
 * @return void
 */
function nggallery_start_upgrade($filepath) {
?>
<div class="wrap">
	<h2><?php _e('Upgrade NextGEN Gallery', 'nggallery') ;?></h2>
	<p><?php ngg_upgrade();?></p>
	<p class="finished"><?php _e('Upgrade finished...', 'nggallery') ;?></p>
	<h3><a class="finished" href="<?php echo $filepath;?>"><?php _e('Continue', 'nggallery'); ?>...</a></h3>
</div>
<?php
}

/**
 * Rebuild slugs for albums, galleries and images via AJAX request
 *
 * @sine 1.7.0
 * @access internal
 */
class ngg_rebuild_unique_slugs {

	function start_rebuild() {
        global $wpdb;

        $total = array();
        // get the total number of images
		$total['images'] = intval( $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->nggpictures") );
        $total['gallery'] = intval( $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->nggallery") );
        $total['album'] = intval( $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->nggalbum") );

		$messages = array(
			'images' => __( 'Rebuild image structure : %s / %s images', 'nggallery' ),
			'gallery' => __( 'Rebuild gallery structure : %s / %s galleries', 'nggallery' ),
            'album' => __( 'Rebuild album structure : %s / %s albums', 'nggallery' ),
		);

?>
<?php

        foreach ( array_keys( $messages ) as $key ) {

    		$message = sprintf( $messages[ $key ] ,
    			"<span class='ngg-count-current'>0</span>",
    			"<span class='ngg-count-total'>" . $total[ $key ] . "</span>"
    		);

    		echo "<div class='$key updated'><p class='ngg'>$message</p></div>";
        }

		$ajax_url = add_query_arg( 'action', 'ngg_rebuild_unique_slugs', admin_url( 'admin-ajax.php' ) );
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	var ajax_url = '<?php echo $ajax_url; ?>',
		_action = 'images',
		images = <?php echo $total['images']; ?>,
		gallery = <?php echo $total['gallery']; ?>,
        album = <?php echo $total['album']; ?>,
        total = 0,
        offset = 0,
		count = 50;

	var $display = $('.ngg-count-current');
    $('.finished, .gallery, .album').hide();
    total = images;

	function call_again() {
		if ( offset > total ) {
		    offset = 0;
            // 1st run finished
            if (_action == 'images') {
                _action = 'gallery';
                total = gallery;
                $('.images, .gallery').toggle();
                $display.html(offset);
                call_again();
                return;
            }
            // 2nd run finished
            if (_action == 'gallery') {
                _action = 'album';
                total = album;
                $('.gallery, .album').toggle();
                $display.html(offset);
                call_again();
                return;
            }
            // 3rd run finished, exit now
            if (_action == 'album') {
    			$('.ngg')
    				.html('<?php _e( 'Done.', 'nggallery' ); ?>')
    				.parent('div').hide();
                $('.finished').show();
    			return;
            }
		}

		$.post(ajax_url, {'_action': _action, 'offset': offset}, function(response) {
			$display.html(offset);

			offset += count;
			call_again();
		});
	}

	call_again();
});
</script>
<?php
	}
}