<?php
/*
* Plugin Name: WPMU Power Tools
* Plugin URI: http://plugins.paidtoblog.com/wpmu-power-tools/
* Description: A few powerfull tools that every WPMU Admin should have.
* Author: Brian Freeman (aka MrBrian)
* Version: 0.5
*/

/*
Change Log:
----------------------------------------------------------------------
----------------------------------------------------------------------

0.5 - 8/18/10
----------------------------------------------------------------------
- Updated for WP 3.0
- Removed performance mode
- Bugfix with progress bar
- Bugfix with unused blogs cleanup

0.4 - 1/04/10
----------------------------------------------------------------------
- Added performance mode for PHP Code Executor for very large/complex WPMU databases, such as multi-db with 4000+ blogs
- Tweaked the progress bar to be more informative during operations
- added $current_site global
- Minor fixes/tweaks

0.3 - 10/02/08
----------------------------------------------------------------------
- Initial Release.

*/


/* Some sample snippet codes for PHP Executor (feel free to send in yours)
*

//The code below will output all users that have the firestats plugin installed.
$plugin_file = 'firestats/firestats-wordpress.php';
if ( @is_plugin_active($plugin_file) ) {
$user = get_users_of_blog($blog_id);
echo 'activated for:'.$user[0]->user_login.'<br>';
}

//Migrate all users of one wordpress theme to another one
$old_theme = get_option('current_theme');
if($old_theme == 'Old Theme Name')
update_option('current_theme', 'New Theme Name');

//For Donncha's Sitewide Tags plugin: Populate tags blog with all posts made before the plugin was added
$posts = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_status = 'publish'" );
if ( !empty($posts) ) {
    foreach ( $posts as $post ) {
        $post_id = $post['ID'];
        if($post_id != 1 && $post_id != 2)
            sitewide_tags_post($post_id, get_post($post_id));
    }
}

*
*/

//------------------------------------------------------------------------//
//---Hook-----------------------------------------------------------------//
//------------------------------------------------------------------------//
add_action( 'admin_menu', 'power_tools_plug_pages' );
//------------------------------------------------------------------------//
//---Functions------------------------------------------------------------//
//------------------------------------------------------------------------//

function power_tools_plug_pages()
{
    add_submenu_page( 'ms-admin.php', 'Power Tools', 'Power Tools', 10, 'power_tools', 'power_tools_page_output' );
}

//------------------------------------------------------------------------//
//---Page Output Functions------------------------------------------------//
//------------------------------------------------------------------------//

function power_tools_page_output()
{
    global $blog_id, $wpdb, $wp_roles, $wp_rewrite, $current_user, $current_site;

?>
<div class="wrap">
<?php
    switch ( $_GET['action'] ) {
            //---------------------------------------------------//
        default:
?>
			<h2><?php _e( 'Power Tools' ) ?></h2>
			<h3><?php _e( 'PHP Code Executor' ) ?></h3>
			<div class="wrap">
            <form method="post" action="ms-admin.php?page=power_tools&action=phpexec">
            <p>
            You can use the form below to execute lines of PHP code on the server once or for each and every blog. This can be highly usefull, for example, if you need make changes to each and every blog automagically. <strong>This tool is intended for advanced users only!</strong></p>
            <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php _e( 'PHP Code:' ) ?></th>
            <td>
            <?php echo htmlentities( '<?php' ); ?>
            <br>
            <textarea name="power_tools_phpexec_code" type="text" rows="5" wrap="soft" id="power_tools_phpexec_code" style="width: 95%"></textarea>
            <br>
            <?php echo htmlentities( '?>' ); ?>
            <br>
            			<b>Globals available:</b> $blog_id, $wpdb, $wp_roles, $wp_rewrite, $current_user, $current_site</td>
            </tr>
            <tr>
            <th scope="row"><?php _e( 'Options:' ) ?></th>
			<td>
			<input type="radio" name="power_tools_phpexec_option" id="power_tools_phpexec_option" value="runonce" checked/> Run this code once (Blog ID: <b><?php echo
            $blog_id; ?></b>)
      <br>
			<input type="radio" name="power_tools_phpexec_option" id="power_tools_phpexec_option" value="runall"/> Execute this code on all blogs
			</td>
			</tr>
            </table>
            <p class="submit">
            <input type="submit" name="Submit" value="<?php _e( 'Execute' ) ?>" />
            </p>
            </form>
            </div>
            
            <br>
            
			<h3><?php _e( 'WPMU PowerClean' ) ?></h3>
			<div class="wrap">
            <form method="post" action="ms-admin.php?page=power_tools&action=cleanup">
            <p>This is a very powerful cleanup tool that will remove unnecessary data left over by WPMU. Select the options below that you want and hit PowerClean to start! <b>Be sure to do a full backup before running this operation!</b></p>
            <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php _e( 'Options:' ) ?></th>
			<td>
			<input type="checkbox" name="power_tools_blogcleanup_option_spamblogs" id="power_tools_blogcleanup_option_spamblogs" /> Blogs marked as spam<br>
			<input type="checkbox" name="power_tools_blogcleanup_option_spamusers" id="power_tools_blogcleanup_option_spamusers" /> Users marked as spam<br>
			<input type="checkbox" name="power_tools_blogcleanup_option_spamcomments" id="power_tools_blogcleanup_option_spamcomments" /> Comments marked as spam/delete <b>(intensive)</b><br>
			<input type="checkbox" name="power_tools_blogcleanup_option_deletedblogs" id="power_tools_blogcleanup_option_deletedblogs" /> Blogs that have been marked for deletion by their owners<br>
			<input type="checkbox" name="power_tools_blogcleanup_option_oldblogs" id="power_tools_blogcleanup_option_oldblogs" /> Blogs with 0 posts that are older than 30 days <b>(intensive)</b><br>
			<input type="checkbox" name="power_tools_blogcleanup_option_oldsignups" id="power_tools_blogcleanup_option_oldsignups" /> Blog/user signups older than 30 days and still awaiting activation via email<br>
			</td>
			</tr>
            </table>
            <p class="submit">
            <input type="submit" name="Submit" value="<?php _e( 'PowerClean' ) ?>" />
            </p>
            </form>
            </div>
			<?php
            break;
            //---------------------------------------------------//
        case "phpexec":
            @set_time_limit(0);
            @ini_set('display_errors','1');

            $phpexec_code = stripslashes( $_POST["power_tools_phpexec_code"] );
            echo '<p>Executing...</p>';
            if ( $_POST['power_tools_phpexec_option'] == 'runonce' ) {
                eval( $phpexec_code );
                echo '<p>Completed. &nbsp;&nbsp;<a href="ms-admin.php?page=power_tools">&laquo; Go back</a></p>';
            } else {
                $blogs = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
                $blog_count = count( $blogs );
                foreach ( $blogs as $blog ) {
                  $blog_exec_count++;
                  power_tools_progress_bar( $blog_exec_count, $blog_count, "Current blog ID: <b>$blog</b>" );
                  switch_to_blog( $blog );
                  eval( $phpexec_code );
                }
                echo '<p>Completed operation on ' . number_format( $blog_count ) .
                    ' blogs. &nbsp;&nbsp;<a href="ms-admin.php?page=power_tools">&laquo; Go back</a></p>';
            }
            break;
        case "cleanup":
            @set_time_limit(0);
            @ini_set('display_errors','1');

            $cleanup_spamblogs = isset( $_POST['power_tools_blogcleanup_option_spamblogs'] ) ? true:
            false;
            $cleanup_spamusers = isset( $_POST['power_tools_blogcleanup_option_spamusers'] ) ? true:
            false;
            $cleanup_spamcomments = isset( $_POST['power_tools_blogcleanup_option_spamcomments'] ) ? true:
            false;
            $cleanup_deletedblogs = isset( $_POST['power_tools_blogcleanup_option_deletedblogs'] ) ? true:
            false;
            $cleanup_oldblogs = isset( $_POST['power_tools_blogcleanup_option_oldblogs'] ) ? true:
            false;
            $cleanup_oldsignups = isset( $_POST['power_tools_blogcleanup_option_oldsignups'] ) ? true:
            false;

            if ( $cleanup_spamblogs ) {
                $blogs = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs WHERE spam = 1" );
                $cnt = count($blogs);
                if ( !empty($blogs) ) {
                    foreach ( $blogs as $blog ) {
                        power_tools_progress_bar( $cleanup_spamblogs_count, $cnt, 'Cleaning blogs marked as spam... (blog ID: <b>' . $blog . '</b>)' );
                        wpmu_delete_blog( $blog, true );
                        $cleanup_spamblogs_count++;
                    }
                }
            }

            if ( $cleanup_spamusers ) {
                $users = $wpdb->get_col( "SELECT ID FROM $wpdb->users WHERE spam = 1" );
                $cnt = count($users);
                if ( !empty($users) ) {
                    foreach ( $users as $user ) {
                        power_tools_progress_bar( $cleanup_spamusers_count, $cnt, 'Cleaning users marked as spam... (user ID: <b>' . $user . '</b>)' );
                        wpmu_delete_user( $user );
                        $cleanup_spamusers_count++;
                    }
                }
            }

            if ( $cleanup_spamcomments ) {
                $blogs = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
                $cnt = count($blogs);
                if ( !empty($blogs) ) {
                    foreach ( $blogs as $blog ) {
                        power_tools_progress_bar( $cleanup_spamcomments_count, $cnt, 'Cleaning comments marked as spam... (blog ID: <b>' . $blog . '</b>)' );
                        $wpdb->query( "DELETE FROM {$wpdb->base_prefix}{$blog}_comments WHERE comment_approved = 'spam' OR comment_approved = 'delete'" );
                        $cleanup_spamcomments_count = ( $cleanup_spamcomments_count + $wpdb->rows_affected );
                    }
                }
            }


            if ( $cleanup_deletedblogs ) {
                $blogs = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs WHERE deleted = 1" );
                $cnt = count($blogs);
                if ( !empty($blogs) ) {
                    foreach ( $blogs as $blog ) {
                        power_tools_progress_bar( $cleanup_deletedblogs_count, $cnt, 'Cleaning blogs marked for deletion... (blog ID: <b>' . $blog . '</b>)' );
                        wpmu_delete_blog( $blog, true );
                        $cleanup_deletedblogs_count++;
                    }
                }
            }

            if ( $cleanup_oldblogs ) {
                $blogs = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs WHERE DATE(registered) < DATE_SUB(curdate(), INTERVAL 30 DAY)" );
                $cnt = count($blogs);
                if ( !empty($blogs) ) {
                    foreach ( $blogs as $blog ) {
                        power_tools_progress_bar( $cleanup_oldblogs_count, $cnt, 'Cleaning blogs older than 30 days with 0 posts... (checking blog ID: <b>' . $blog . '</b>)' );
                        $posts = $wpdb->get_col( "SELECT ID FROM {$wpdb->base_prefix}{$blog}_posts" );
                        if ( $posts[2]['ID'] == 3 ) { //wordpress by default adds one post and one page, so we check for a post ID of 3
                            wpmu_delete_blog( $blog, true );
                            $cleanup_oldblogs_count++;
                        }
                    }
                }
            }

            if ( $cleanup_oldsignups ) {
                power_tools_progress_bar( 1, 100, 'Cleaning stale signups...' );
                $wpdb->query( "DELETE FROM $wpdb->signups WHERE active = 0 AND DATE(registered) < DATE_SUB(curdate(), INTERVAL 30 DAY)" );
                $cleanup_oldsignups_count = $wpdb->rows_affected;
                power_tools_progress_bar( 1, 100, 'Finished.' );
            }

            echo '<br>-----Results of cleanup-----<br>';
            echo number_format( $cleanup_spamblogs_count ) . ' spam blogs.<br>';
            echo number_format( $cleanup_spamusers_count ) . ' spam users.<br>';
            echo number_format( $cleanup_spamcomments_count ) . ' spam comments.<br>';
            echo number_format( $cleanup_deletedblogs_count ) . ' deleted blogs.<br>';
            echo number_format( $cleanup_oldblogs_count ) . ' abandoned blogs.<br>';
            echo number_format( $cleanup_oldsignups_count ) . ' old inactive signups.<br>';
            echo '<p><a href="ms-admin.php?page=power_tools">&laquo; Go back</a></p>';
    }
?>
</div>
<?php
}

function power_tools_progress_bar( $intCurrentCount = 1, $intTotalCount = 100, $strStatus )
{
    static $intNumberRuns = 0;
    static $intDisplayedCurrentPercent = 0;
    $strProgressBar = '';
    $dblPercentIncrease = ( 100 / $intTotalCount );
    $intCurrentPercent = intval( $intCurrentCount * $dblPercentIncrease );
    $intNumberRuns++;

    if ( 1 == $intNumberRuns ) {
        $strProgressBar = <<< BAR
<table width='50%' id='progress_bar' summary='progress_bar' align='center'><tbody><tr>
<td id='progress_bar_complete' width='0%' align='center' style='background:#CCFFCC;'>&nbsp;</td>
<td style='background:#FFCCCC;'>&nbsp;</td>
</tr></tbody></table>
<p id='progress_bar_status'>&nbsp;</p>
<script type='text/javascript' language='javascript'>
function power_tools_progress_bar_update(intCurrentPercent,strStatus)
{
    document.getElementById('progress_bar_complete').style.width = intCurrentPercent+'%';
    document.getElementById('progress_bar_complete').innerHTML = intCurrentPercent+'%';
    document.getElementById('progress_bar_status').innerHTML = strStatus;
}
</script>
BAR;
    }
    else
        if ( $intDisplayedCurrentPercent <> $intCurrentPercent ) {
            $intDisplayedCurrentPercent = $intCurrentPercent;
            $strProgressBar = <<< BAR
<script type='text/javascript' language='javascript'>
power_tools_progress_bar_update($intCurrentPercent,'$strStatus');
</script>
BAR;
        }
    echo $strProgressBar;
    flush();
    ob_flush();
}

?>
