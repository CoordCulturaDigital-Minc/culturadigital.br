<?php
function xemele_options(){
$themename = "xemele";
$shortname = "xemele";
$xl_categories_obj = get_categories('hide_empty=0');
$xl_categories = array();
foreach ($xl_categories_obj as $xl_cat) {
	$xl_categories[$xl_cat->cat_ID] = $xl_cat->category_nicename;
}
$categories_tmp = array_unshift($xl_categories, "Selecione a categoria:");	
$number_entries = array("Selecione o número:","1","2","3","4","5","6","7","8","9","10", "12","14", "16", "18", "20" );
$options = array (

    array(  "name" => "Opções do Slide",
            "type" => "heading",
			"desc" => "Customize os slides dos Destaques e infome o número de destaques que irá aparecer.",
       ),

	array( 	"name" => "Categoria do Slide",
			"desc" => "Selecione a categoria que você gostaria que aparecesse como destaque no slide.",
			"id" => $shortname."_gldcat",
			"std" => "Selecione a categoria:",
			"type" => "select",
			"options" => $xl_categories),
			
	array(	"name" => "Número de posts no Slide",
			"desc" => "Selecione o número de posts do Slide.",
			"id" => $shortname."_gldct",
			"std" => "Selecione o número:",
			"type" => "select",
			"options" => $number_entries),
			
			
	   
);

update_option('xemele_template',$options);update_option('xemele_themename',$themename);update_option('xemele_shortname',$shortname);  
		  
	}
add_action('init','xemele_options');
function mytheme_add_admin() {

       $options =  get_option('xemele_template'); $themename =  get_option('xemele_themename');$shortname =  get_option('xemele_shortname');    

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

                header("Location: themes.php?page=controlpanel.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
                delete_option( $value['id'] ); 
                update_option( $value['id'], $value['std'] );}

            header("Location: themes.php?page=controlpanel.php&reset=true");
            die;

        }
    }

      add_theme_page($themename." Options", "$themename Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');

}

function mytheme_admin() {

    $options =  get_option('xemele_template');$themename =  get_option('xemele_themename');$shortname =  get_option('xemele_shortname');   

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' opções salvas.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' opções resetadas.</strong></p></div>';
    
    
?>
<div class="wrap">
<h2><b><?php echo $themename; ?> Opções do tema</b></h2>

<form method="post">

<table class="optiontable">

<?php foreach ($options as $value) { 
    
	
if ($value['type'] == "text") { ?>
        
<tr align="left"> 
    <th scope="row"><?php echo $value['name']; ?>:</th>
    <td>
        <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" size="40" />
				
    </td>
	
</tr>
<tr><td colspan=2> <small><?php echo $value['desc']; ?> </small> <hr /></td></tr>

<?php } elseif ($value['type'] == "textarea") { ?>
<tr align="left"> 
    <th scope="row"><?php echo $value['name']; ?>:</th>
    <td>
                   <textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="50" rows="8"/>
				   <?php if ( get_settings( $value['id'] ) != "") { echo stripslashes (get_settings( $value['id'] )); } 
				   else { echo $value['std']; 
				   } ?>
</textarea>

				
    </td>
	
</tr>
<tr><td colspan=2> <small><?php echo $value['desc']; ?> </small> <hr /></td></tr>


<?php } elseif ($value['type'] == "select") { ?>

    <tr align="left"> 
        <th scope="top"><?php echo $value['name']; ?>:</th>
	        <td>
            <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                <?php foreach ($value['options'] as $option) { ?>
                <option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; }?>><?php echo $option; ?></option>
                <?php } ?>
            </select>
			
        </td>
	
</tr>
<tr><td colspan=2> <small><?php echo $value['desc']; ?> </small> <hr /></td></tr>



<?php } elseif ($value['type'] == "heading") { ?>

   <tr valign="top"> 
		    <td colspan="2" style="text-align: left;"><h2><?php echo $value['name']; ?></h2></td>
		</tr>
<tr><td colspan=2> <small> <p style="margin:0 0;" > <?php echo $value['desc']; ?> </P> </small> <hr /></td></tr>

<?php } ?>
<?php 
}
?>
</table>
<p class="submit">
<input name="save" type="submit" value="Save changes" />    
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>

<?php
}
add_action('admin_menu', 'mytheme_add_admin'); ?>
