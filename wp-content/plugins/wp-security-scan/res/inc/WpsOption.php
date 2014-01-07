<?php
/**
 * Created by JetBrains PhpStorm.
 * User: kos
 * Date: 10/12/13
 * Time: 8:04 AM
 * To change this template use File | Settings | File Templates.
 */

class WpsOption
{
    static function getOption($name, $default = false, $blogID = 1){
        if(wpsIsMultisite()){
            return get_blog_option($blogID, $name, $default);
        }
        return get_option($name, $default);
    }
    static function addOption($name,$value,$blogID = 1)
    {
        if(wpsIsMultisite()){
            return add_blog_option($blogID, $name, $value);
        }
        return add_option($name, $value);
    }

    static function deleteOption($name, $blogID = 1)
    {
        if(wpsIsMultisite()){
            return delete_blog_option($blogID, $name);
        }
        return delete_option($name);
    }

    static function updateOption($name,$value,$blogID = 1)
    {
        if(wpsIsMultisite()){
            return update_blog_option($blogID, $name, $value);
        }
        return update_option($name, $value);
    }
}