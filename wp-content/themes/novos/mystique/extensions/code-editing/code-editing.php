<?php /* Mystique/digitalnature */


function mystique_codemirror_admin_js(){
  wp_enqueue_script('codemirror', THEME_URL.'/extensions/code-editing/codemirror/js/codemirror.js');
}

function mystique_codemirror_admin_init_js(){
  $codemirror_path = THEME_URL.'/extensions/code-editing/codemirror';
  ?>
  // codemirror
  var editor_footer_content = CodeMirror.fromTextArea('opt_footer_content', {
   height: "200px",
   parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js", "parsehtmlmixed.js"],
   stylesheet: ["<?php echo $codemirror_path; ?>/css/xmlcolors.css", "<?php echo $codemirror_path; ?>/css/jscolors.css", "<?php echo $codemirror_path; ?>/css/csscolors.css"],
   path: "<?php echo $codemirror_path; ?>/js/",
   iframeClass: 'iframe_footer_content'
  });

  <?php if (current_user_can('edit_themes')): ?>
  var editor_functions = CodeMirror.fromTextArea('opt_functions', {
   height: "300px",
   parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js", "parsehtmlmixed.js", "../contrib/php/js/tokenizephp.js", "../contrib/php/js/parsephp.js",
                   "../contrib/php/js/parsephphtmlmixed.js"],
   stylesheet: ["<?php echo $codemirror_path; ?>/css/xmlcolors.css", "<?php echo $codemirror_path; ?>/css/jscolors.css", "<?php echo $codemirror_path; ?>/css/csscolors.css", "<?php echo $codemirror_path; ?>/contrib/php/css/phpcolors.css"],
   path: "<?php echo $codemirror_path; ?>/js/",
   iframeClass: 'iframe_functions'
  });
  <?php endif; ?>

  var editor_user_css = CodeMirror.fromTextArea('opt_user_css', {
   height: "540px",
   parserfile: "parsecss.js",
   stylesheet: "<?php echo $codemirror_path; ?>/css/csscolors.css",
   path: "<?php echo $codemirror_path; ?>/js/",
   iframeClass: 'iframe_user_css'
  });

  jQuery(document).ready(function () {
    jQuery('.CodeMirror-wrapping:not(.processed)').TextAreaResizer();
  });  

  <?php
}

add_action("admin_print_scripts", 'mystique_codemirror_admin_js');
add_action("mystique_admin_init_js","mystique_codemirror_admin_init_js");

?>