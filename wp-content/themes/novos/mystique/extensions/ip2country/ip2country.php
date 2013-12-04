<?php /* Mystique/digitalnature */


function mystique_get_flag($ip) {
  require_once('ip2c/ip2c.php');
  if (isset($GLOBALS['ip2c'])):
   global $ip2c;
  else:
   $ip2c = new ip2country(TEMPLATEPATH.'/extensions/ip2country/ip2c/ip-to-country.bin');
   $GLOBALS['ip2c'] = $ip2c;
  endif;

  $country = $ip2c->get_country($ip);
  if ($country):
   $code = strtolower($country['id2']);
   $name = ucwords(strtolower($country['name']));
   return array('code' => $code, 'name' => $name);
  else:
   return false;
  endif;
}

function mystique_author_flag($authorlink){
  if(get_mystique_option('comment_author_country')):
    $flag = @mystique_get_flag(get_comment_author_IP());
    if (!empty($flag['code'])) $authorlink .= '<abbr class="flag '.$flag['code'].'" title="'.$flag['name'].'">&nbsp;</abbr>';
  endif;
  return $authorlink;  
}

function mystique_ip2c_comment_class($class){
  if(get_mystique_option('comment_author_country')):
    $flag = @mystique_get_flag(get_comment_author_IP());
    if (!empty($flag['code'])) $class[] = ' country-'.$flag['code'];
  endif;
  return $class;
}

function mystique_ip2c_css(){
  if(get_mystique_option('comment_author_country') && (is_single() || is_page())): ?>
@import "<?php echo THEME_URL; ?>/extensions/ip2country/flags.css";
<?php
  endif;
}

function mystique_ip2c_admin(){ ?>
   <tr>
    <th scope="row"><p><?php _e("Show comment author's country flag","mystique"); ?><span><?php _e("May slow down page loading","mystique"); ?></span></p></th>
    <td><input name="comment_author_country" type="checkbox" class="checkbox" value="1" <?php checked( '1', get_mystique_option('comment_author_country')) ?> /></td>
   </tr> <?php
}

function mystique_ip2c_settings($defaults){
  $defaults['comment_author_country'] = 0;
  return $defaults;
}

add_filter('mystique_comment_author_link','mystique_author_flag');
add_filter('comment_class','mystique_ip2c_comment_class');
add_action('mystique_css','mystique_ip2c_css');

add_filter('mystique_default_settings','mystique_ip2c_settings');
add_action('mystique_admin_content','mystique_ip2c_admin');


?>