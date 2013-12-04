<?php

/**
 * Project:     Securimage: A PHP class for creating and managing form CAPTCHA images<br />
 * File:        securimage.php<br />
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or any later version.<br /><br />
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.<br /><br />
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA<br /><br />
 *
 * Any modifications to the library should be indicated clearly in the source code
 * to inform users that the changes are not a part of the original software.<br /><br />
 *
 * If you found this script useful, please take a quick moment to rate it.<br />
 * http://www.hotscripts.com/rate/49400.html  Thanks.
 *
 * @link http://www.phpcaptcha.org Securimage PHP CAPTCHA
 * @link http://www.phpcaptcha.org/latest.zip Download Latest Version
 * @link http://www.phpcaptcha.org/Securimage_Docs/ Online Documentation
 * @copyright 2009 Drew Phillips
 * @author drew010 <drew@drew-phillips.com>
 * @version 2.0 BETA (November 15, 2009)
 * @package Securimage
 *
 */

include 'securimage.php';

$img = new securimage();


//Change some settings
$img->code_length = 4;
$img->image_width = 175;
$img->image_height = 60;
$img->perturbation = 0.6; // 1.0 = high distortion, higher numbers = more distortion

$img->charset = 'ABCDEFHKLMNPRSTUVWYZ234578';
//$img->charset = 'ABCDEFHKLMNPRSTUVWYZabcdefhkmnpstuvwyz234578';
$img->ttf_file = getcwd() . '/ttffonts/AHGBold.ttf';   // single font

//$img->ttf_font_directory = getcwd() . '/ttffonts';  // random fonts
//$img->ttf_file = $img->getFontFromDirectory(); // random fonts

$img->multi_text_color = array(
'#6666FF','#660000','#3333CC','#993300','#0060CC',
'#339900','#6633CC','#330000','#006666','#CC3366',
);
$img->use_multi_text = true;
$img->use_transparent_text = true;
$img->text_transparency_percentage = 20;
$img->num_lines = 3;
$img->line_color = new Securimage_Color(rand(0, 64), rand(64, 128), rand(128, 255));
$img->image_type = 'png';
$img->background_directory = getcwd() . '/backgrounds';
$img->show(''); // alternate use:  $img->show('./backgrounds/1.gif');


?>