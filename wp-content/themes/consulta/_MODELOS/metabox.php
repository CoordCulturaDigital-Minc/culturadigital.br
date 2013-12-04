<?php

add_action( 'add_meta_boxes', 'SLUG_add_custom_box' );

/* Do something with the data entered */
add_action( 'save_post', 'SLUG_save_postdata' );

/* Adds a box to the main column on the Post and Page edit screens */
function SLUG_add_custom_box() {
    add_meta_box( 
        'SLUG',
        'Nome do box',
        'SLUG_inner_custom_box_callback_function',
        'post', // em que post type eles entram?
        'side' // onde? side, normal, advanced
        //,'default' // 'high', 'core', 'default' or 'low'
        //,array('variáve' => 'valor') // variaveis que serão passadas para o callback
    );

}

/* Prints the box content */
function SLUG_inner_custom_box_callback_function() {
    global $post;
    // Use nonce for verification
    wp_nonce_field( 'save_SLUG', 'SLUG_noncename' );
    
    $meta_name = '_exemplo';
    
    // The actual fields for data entry
    echo '<b><label for="SLUG">';
       echo 'Nome do campo...';
    echo '</label></b>';
    
    $video = get_post_meta($post->ID, $meta_name, true);
    
    ?>
    
    <input type="text" id="<?php echo $meta_name; ?>" name="<?php echo $meta_name; ?>" value="<?php echo $video; ?>" size="25" />
    
    <?php
    
}

/* When the post is saved, saves our custom data */
function SLUG_save_postdata( $post_id ) {
    
    $meta_name = '_exemplo';
    
    // verify if this is an auto save routine. 
    // If it is our form has not been submitted, so we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times

    if ( !wp_verify_nonce( $_POST['SLUG_noncename'], 'save_SLUG' ) )
        return;


    // Check permissions
    if ( 'page' == $_POST['post_type'] ) 
    {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
    }
    else
    {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
    }

    // OK, we're authenticated: we need to find and save the data

    update_post_meta($post_id, $meta_name, trim($_POST[$meta_name]));

    
}

?>
