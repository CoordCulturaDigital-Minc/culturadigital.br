<?php if(! defined('WPS_PLUGIN_PREFIX')) return;
/**
 * Class WsdWatch
 * Static class. Provides common methods to be used to monitor website activity.
 */
class WsdWatch extends WsdPlugin
{
/*
 * PUBLIC METHODS
 * ==============================================
 */

    public static function userPasswordUpdate(){
        add_action('edit_user_profile_update', array(get_class(), '_watchUserInfoUpdated'));
        add_action('personal_options_update', array(get_class(), '_watchUserInfoUpdated'));
    }


/*
 * PRIVATE METHODS
 * ==============================================
 */

    // returns array(userName, userRole)
    public static function _getUserInfo($userID)
    {
        global $wpdb;

        $t = wpsGetBasePrefix().'users';
        $username = $wpdb->get_var($wpdb->prepare("SELECT user_login FROM $t WHERE ID=%d",$userID));
        $user = new WP_User( $userID );
        $userRole = (empty($user->roles[0]) ? '' : $user->roles[0]);
        return array(
            'userName' => $username,
            'userRole' => $userRole
        );
    }
    /**
     * @internal
     * @param $userID
     */
    public static function _watchUserInfoUpdated($userID)
    {
        // If an admin user's password has been updated
        if(! empty($_POST['pass1'])){
            $userInfo = self::_getUserInfo($userID);
            $userName = $userInfo['userName'];
            $userRole = $userInfo['userRole'];
            if($userRole == 'administrator')
            {
                global $wpsPluginAlertsArray;
                $actionName = $wpsPluginAlertsArray['watch_admin_password_update']['name'];
                $alertType = $wpsPluginAlertsArray['watch_admin_password_update']['type'];

                if(wpsIsMultisite()){
                    global $wpdb;
                    $blogID = $wpdb->blogid;
                    $blogName = WpsOption::getOption('blogname', $blogID);
                    self::alert($actionName, $alertType, WpsSettings::ALERT_MEDIUM,
                        sprintf(__('Administrator (<strong>%s</strong>) of blog <strong>%s</strong> has updated their password.',WpsSettings::TEXT_DOMAIN), $userName, $blogName),
                        __('<p>This alert is generated every time an administrator\'s password is updated.</p>',WpsSettings::TEXT_DOMAIN));
                }
                else {
                    self::alert($actionName, $alertType, WpsSettings::ALERT_MEDIUM,
                        sprintf(__('Administrator (<strong>%s</strong>) has updated their password.',WpsSettings::TEXT_DOMAIN), $userName),
                        __('<p>This alert is generated every time an administrator\'s password is updated.</p>',WpsSettings::TEXT_DOMAIN));
                }
            }
        }
    }

}