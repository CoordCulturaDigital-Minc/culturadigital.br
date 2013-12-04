<?php
/*
Thank you Philipp Heinze.
*/

function mrt_sub3(){
?>
 <div class=wrap>
                <h2><?php _e('WP - Database Security') ?></h2>
          <div style="height:299px"><br />
<h3><i>Make a backup of your database before using this tool:</i></h3>

<?php /*global $wpdb; 
$mrtright = $wpdb->get_results("SHOW GRANTS FOR '".DB_USER."'@'".DB_HOST."'", ARRAY_N); 
echo "rights: "; 
print_r($mrtright);*/
?>

        <p>Change your database table prefix to mitigate zero-day SQL Injection attacks.</p>
        <p><b>Before running this script:</b>
           <ul><li>wp-config must be set to writable before running this script.</li>
               <li>the database user you're using with WordPress must have ALTER rights</li></ul>

    <form action='' method='post' name='prefixchanging'>
    <?php
    if (function_exists('wp_nonce_field')) {
        wp_nonce_field('prefix-changer-change_prefix');
    }
    ?>
     Change the current:<input type="Text" name="prefix_n" value="<?php echo($GLOBALS['table_prefix']);?>" size="20" maxlength="50"> prefix to something different if it's the default wp_<br />
     Allowed Chars are all latin Alphanumeric Chars as well as the Chars <strong>-</strong> and <strong>_</strong>.
    <input type='submit' name='renameprefix' value='Start Renaming'/>
    </form>

    <?php
    if (isset($_POST['prefix_n'])) {
        check_admin_referer('prefix-changer-change_prefix');
        $wpdb =& $GLOBALS['wpdb'];
        $newpref = ereg_replace("[^0-9a-zA-Z_-]", "", $_POST['prefix_n']);
        //checking if user has enough rights to alter the Tablestructure
        $rights = $wpdb->get_results("SHOW GRANTS FOR '".DB_USER."'@'".DB_HOST."'", ARRAY_N);
        foreach ($rights as $right) {
            if (ereg("ALTER(.*)(\*|`".str_replace("_", "\\_", DB_NAME)."`)\.(\*|`".DB_HOST."`) TO '".DB_USER."'@'".DB_HOST."'", $right[0]) || ereg("ALL PRIVILEGES ON (\*|`".str_replace("_", "\\_", DB_NAME)."`)\.(\*|`".DB_HOST."`) TO '".DB_USER."'@'".DB_HOST."'", $right[0])) {
                $rightsenough = true;
                $rightstomuch = true;
                break;
            } else {
                if (ereg("ALTER(.*)`".DB_NAME."`", $right[0])) {
                    $rightsenough = true;
                    break;
                }
            }
        }
        if (!isset($rightsenough) && $rightsenough != true) {
            exit('<font color="#ff0000">Your User which is used to access your Wordpress Tables/Database, hasn\'t enough rights( is missing ALTER-right) to alter your Tablestructure.  Please visit the plugin <a href="http://semperfiwebdesign.com/documentation/wp-security-scan/change-wordpress-database-table-name-prefix/" target=_blank">documentation</a> for more information.  If you believe you have alter rights, please <a href="http://semperfiwebdesign.com/contact/">contact</a> the plugin author for assistance.<br />');
        }
        if (isset($rightstomuch) && $rightstomuch === true) {
            echo ('<font color="#FF9B05">Your currently used User to Access the Wordpress Database, holds too many rights. '.
                'We suggest that you limit his rights or to use another User with more limited rights instead, to increase your Security.</font><br />');
        }
        if ($newpref == $GLOBALS['table_prefix']) {
            exit ("No change: Please select a new table_prefix value.</div>");
        } elseif (strlen($newpref) < strlen($_POST['prefix_n'])){
            echo ("You used some Chars which aren't allowed within Tablenames".
            "The sanitized prefix is used instead: " . $newpref);
        }

        echo("<h2>Started Prefix Changer:</h2>");

        //we rename the tables before we change the Config file, so We can aviod changed Configs, without changed prefixes.
        echo("<h3>&nbsp;&nbsp;Start Renaming of Tables:</h3>");
        $oldtables = $wpdb->get_results("SHOW TABLES LIKE '".$GLOBALS['table_prefix']."%'", ARRAY_N);//retrieving all tables named with the prefix on start
        $table_c = count($oldtables);
        $table_s = 0;//holds the count of successful changed tables.
        $table_f[] = '';//holds all table names which failed to be changed
        for($i = 0; $i < $table_c; $i++) {//renaming each table to the new prefix
            $wpdb->hide_errors();
            $table_n = str_replace($GLOBALS['table_prefix'], $newpref, $oldtables[$i][0]);
            echo "&nbsp;&nbsp;&nbsp;Renaming ".$oldtables[$i][0]." to $table_n:";
            $table_r = $wpdb->query("RENAME TABLE ".$oldtables[$i][0]." TO $table_n");
            if ($table_r === 0) {
                echo ('<font color="#00ff00"> Success</font><br />');
                $table_s++;
            } elseif ($table_r === FALSE) {
                echo ('<font color="#ff0000"> Failed</font><br />');
                $table_f[] = $oldtables[$i][0];
            }
        }//changing some "hardcoded" wp values within the tables
        echo ("<h3>&nbsp;&nbsp;Start changing Databasesettings:</h3>");
        if ($wpdb->query($wpdb->prepare("UPDATE ".$newpref."options SET option_name='".$newpref."user_roles' WHERE option_name='".$GLOBALS['table_prefix']."user_roles' LIMIT 1")) <> 1) {
            echo ('&nbsp;&nbsp;&nbsp;Changing values in table '.$newpref.'options: 1/1 <font color="#ff0000">Failed</font><br />');
        } else {
            echo ('&nbsp;&nbsp;&nbsp;Changing values in table '.$GLOBALS['table_prefix'].'options 1/1: <font color="#00ff00">Success</font><br />');
        }
        if ($wpdb->query($wpdb->prepare("UPDATE ".$newpref."usermeta SET meta_key='".$newpref."capabilities' WHERE meta_key='".$GLOBALS['table_prefix']."capabilities'") <> 1)) {
            echo ('&nbsp;&nbsp;&nbsp;Changing values in table '.$GLOBALS['table_prefix'].'usermeta 1/3: <font color="#ff0000">Failed</font><br />');
        } else {
            echo ('&nbsp;&nbsp;&nbsp;Changing values in table '.$GLOBALS['table_prefix'].'usermeta 1/3: <font color="#00ff00">Success</font><br />');
        }
        if ($wpdb->query($wpdb->prepare("UPDATE ".$newpref."usermeta SET meta_key='".$newpref."user_level' WHERE meta_key='".$GLOBALS['table_prefix']."user_level'")) === FALSE)
        {
            echo ('&nbsp;&nbsp;&nbsp;Changing values in table '.$GLOBALS['table_prefix'].'usermeta 2/3: <font color="#ff0000">Failed</font><br />');
        } else {
            echo ('&nbsp;&nbsp;&nbsp;Changing values in table '.$GLOBALS['table_prefix'].'usermeta 2/3: <font color="#00ff00">Success</font><br />');
        }
        if ($wpdb->query($wpdb->prepare("UPDATE ".$newpref."usermeta SET meta_key='".$newpref."autosave_draft_ids' WHERE meta_key='".$GLOBALS['table_prefix']."autosave_draft_ids'")) === 0) {
            echo ('&nbsp;&nbsp;&nbsp;Changing values in table '.$GLOBALS['table_prefix'].'usermeta 3/3: <font color="#000000">Value doesn\'t exist</font><br />');
        } else {
            echo ('&nbsp;&nbsp;&nbsp;Changing values in table '.$GLOBALS['table_prefix'].'usermeta 3/3: <font color="#00ff00">Success</font><br />');
        }
        
        if ($table_s == 0) {
            exit('<font color="#ff0000">Some Error occured, it wasn\'t possible to change any Tableprefix. Please retry, no changes are done to your wp-config File.</font><br />');
        } elseif ($table_s < $table_c) {
            echo('<font color="#ff0000">It wasn\'t possible to rename some of your Tables prefix. Please change them manually. Following you\'ll see all failed tables:<br />');
            for ($i = 1; $i < count($tables_f); $i++) {
                echo ($tables_f[$i])."<br />";
            }
            exit('No changes where done to your wp-config File.</font><br />');
        }

        echo("<h3>Changing Config File:</h3>");
        $conf_f = "../wp-config.php";

        @chmod($conf_f, 0777);//making the the config readable to change the prefix
        if (!is_writeable($conf_f)) {//when automatic config file changing isn't possible the user get's all needed information to do it manually
            echo('&nbsp;&nbsp;1/1 file writeable: <font color="#ff0000">Not Writeable</font><br />');
            echo('<b>Please make your wp-config.php file writable for this process.</b>');
            die("</div>");
        } else {//changing if possible the config file automatically
            echo('&nbsp;&nbsp;1/3 file writeable: <font color="#00ff00"> Writeable</font><br />');
            $handle = @fopen($conf_f, "r+");
            if ($handle) {
                while (!feof($handle)) {
                    $lines[] = fgets($handle, 4096);
                }//while feof
                fclose($handle);
                $handle = @fopen($conf_f, "w+");
                foreach ($lines as $line) {
                    if (strpos($line, $GLOBALS['table_prefix'])) {
                        $line = str_replace($GLOBALS['table_prefix'], $newpref, $line);
                        echo('&nbsp;&nbsp;2/3 <font color="#00ff00">table prefix changed!</font><br />');
                    }//if strpos
                    fwrite($handle, $line);
                }//foreach $lines
                fclose($handle);
                if (chmod ($conf_f, 0644)) {
                    echo('&nbsp;&nbsp;3/3 <font color="#00ff00">Config files permission set to 644, for security purpose.</font><br />');
                } else {
                    echo ('&nbsp;&nbsp;3/3 wasn\'t able to set chmod to 644, please check if your files permission is set back to 644!<br />');
                }//if chmod
            }//if handle
        }//if is_writeable
        
    }//if prefix
    ?>
   </div>
   Plugin by <a href="http://semperfiwebdesign.com/" title="Semper Fi Web Design">Semper Fi Web Design</a>
        </div>
<?php
}//function prefix_changer
?>
