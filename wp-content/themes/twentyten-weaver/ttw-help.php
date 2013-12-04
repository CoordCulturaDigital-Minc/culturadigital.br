<?php

function ttw_help_admin() {
    /* admin tab for help */

    $readme = get_bloginfo('stylesheet_directory')."/readme.html";
?>
    <h3>Help</h3>
    <p>More help is available at the <a href="http://wpweaver.info" target="_blank"><strong>WP Weaver web site</strong></a>, which includes
    a support forum.</p>
    <h4>Weaver Main Options Summary</h4>
    <p style="text-align:center;"><img src="<?php bloginfo('stylesheet_directory');?>/images/ttw-options.png" /></p>
    <h4>Weaver Help File</h4>
    <?php
    printf('<iframe id="previewhelp" name="previewhelp" src="%s?tempreview=true" style="width:100%%;height:600px;border: 0px"></iframe>',$readme);
    echo "\n";
    show_bullets();
    echo("<hr>\n");
}

function show_bullets()
{
    $where = get_bloginfo('stylesheet_directory') . '/images/bullets/';

    printf("<h4>Widget List Bullet Examples (only black shown)</h4>\n");

printf('| <img src="%s" /> - %s ',$where.'arrow1-black.gif','arrow1');
printf('| <img src="%s" /> - %s ',$where.'arrow2-black.gif','arrow2');
printf('| <img src="%s" /> - %s ',$where.'arrow3-black.gif','arrow3');
printf('| <img src="%s" /> - %s ',$where.'arrow4-black.gif','arrow4');
printf('| <img src="%s" /> - %s ',$where.'arrow5-black.gif','arrow5'); echo("|<br />\n");
printf('| <img src="%s" /> - %s ',$where.'check1-black.gif','check1');
printf('| <img src="%s" /> - %s ',$where.'circle1-black.gif','circle1');
printf('| <img src="%s" /> - %s ',$where.'circle2-black.gif','circle2');
printf('| <img src="%s" /> - %s ',$where.'diamond1-black.gif','diamond1');
printf('| <img src="%s" /> - %s ',$where.'diamond2-black.gif','diamond2'); echo("|<br />\n");
printf('| <img src="%s" /> - %s ',$where.'plus1-black.gif','plus1');
printf('| <img src="%s" /> - %s ',$where.'square1-black.gif','square1');
printf('| <img src="%s" /> - %s ',$where.'square2-black.gif','square2');
printf('| <img src="%s" /> - %s ',$where.'square3-black.gif','square3'); echo("|<br />\n");
printf('| <img src="%s" /> - %s ',$where.'star1-black.gif','star1');
printf('| <img src="%s" /> - %s ',$where.'star2-black.gif','star2');
printf('| <img src="%s" /> - %s ',$where.'star3-black.gif','star3'); echo("|<br />\n");

}

function ttw_snippets_admin() {
    /* admin tab for snippets */
    global $ttw_options;
?>

    <h3>Snippets</h3>
     <iframe id="previewhelp" name="previewhelp" src="<?php bloginfo('stylesheet_directory'); ?>/snippets.html?tempreview=true" style="width:100%;height:600px;border: 0px"></iframe>
    <?php
}

?>
