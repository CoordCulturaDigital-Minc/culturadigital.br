<?php
class SLUG {
    const NAME = 'SLUGs';
    const MENU_NAME = 'SLUG';

    /**
     * Slug do post type: deve conter somente minúscula 
     * @var unknown_type
     */
    protected static $post_type;
    
    static function init(){
        // o slug do post type
        self::$post_type = strtolower(__CLASS__);
        
        add_action( 'init', array(self::$post_type, 'register') ,0);
        
        //add_action( 'add_meta_boxes', array(__CLASS__, 'register_metabox_video_url') ,0);
        
        //add_action( 'save_post', array(__CLASS__, 'metabox_video_save_postdata') );
        // descomente se precisar de taxonomias e configure as taxonomias na funcao register_taxonomies
        //add_action( 'init', array(__CLASS__, 'register_taxonimies') ,0);
        
        //add_filter('menu_order', array(self::$post_type, 'change_menu_label'));
        //add_filter('custom_menu_order', array(self::$post_type, 'custom_menu_order'));
    }
    
    static function register(){
        register_post_type(self::$post_type, array(
                
                'labels' => array(
                                    'name' => _x(self::NAME, 'post type general name'),
                                    'singular_name' => _x('SLUG', 'post type singular name'),
                                    'add_new' => _x('Adicionar Novo', 'image'),
                                    'add_new_item' => __('Adicionar novo SLUG'),
                                    'edit_item' => __('Editar SLUG'),
                                    'new_item' => __('Novo SLUG'),
                                    'view_item' => __('Ver SLUG'),
                                    'search_items' => __('Search SLUGs'),
                                    'not_found' =>  __('Nenhum SLUG Encontrado'),
                                    'not_found_in_trash' => __('Nenhum SLUG na Lixeira'),
                                    'parent_item_colon' => ''
                                 ),
                 'public' => true,
                 'rewrite' => array('SLUG' => 'SLUG'),
                 'capability_type' => 'post',
                 'hierarchical' => false,
                 'map_meta_cap ' => true,
                 'menu_position' => 6,
                 'supports' => array(
                     	'title',
                     	'editor',
                     	'excerpt',
                     	'author',
                     	'comments',
                 ),
                 'taxonomies' => array('post_tag')
            )
        );
    }
    
    static function register_taxonomies(){
        $labels = array(
            'name' => _x( 'Taxonomias', 'taxonomy general name' ),
            'singular_name' => _x( 'Taxonomia', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Taxonomias' ),
            'all_items' => __( 'All Taxonomias' ),
            'parent_item' => __( 'Parent Taxonomia' ),
            'parent_item_colon' => __( 'Parent Taxonomia:' ),
            'edit_item' => __( 'Edit Taxonomia' ), 
            'update_item' => __( 'Update Taxonomia' ),
            'add_new_item' => __( 'Add New Taxonomia' ),
            'new_item_name' => __( 'New Taxonomia Name' ),
        ); 	

        register_taxonomy('taxonomia',self::$post_type, array(
                'hierarchical' => false,
                'labels' => $labels,
                'show_ui' => true,
                'query_var' => true,
                'rewrite' => true,
            )
        );
    }
    
    static function change_menu_label($stuff) {
        global $menu,$submenu;
        foreach ($menu as $i=>$mi){
            if($mi[0] == self::NAME){
                $menu[$i][0] = self::MENU_NAME;
            }
        }
        return $stuff;
    }
    
    static function custom_menu_order() {
        return true;
    }
    
    static function register_metabox_video_url(){
         add_meta_box( 
            'metabox_video_url',
            'URL do vídeo',
            array(__CLASS__, 'metabox_video_url_callback'),
            self::$post_type, // em que post type eles entram?
            'normal' // onde? side, normal, advanced
            //,'default' // 'high', 'core', 'default' or 'low'
            //,array('variáve' => 'valor') // variaveis que serão passadas para o callback
        );
    }
    
    static function metabox_video_url_callback(){
        global $post;
        // Use nonce for verification
        wp_nonce_field( 'save_metabox_video_url', 'metabox_video_url_noncename' );
        
        $meta_name = 'video_url';
        
        $video = get_post_meta($post->ID, $meta_name, true);
        
        ?>
        <label> URL do vídeo: 
        <input type="text" id="<?php echo $meta_name; ?>" name="<?php echo $meta_name; ?>" value="<?php echo $video; ?>" size="25" />
        </label>
        <?php
    }
    
    static function metabox_video_save_postdata( $post_id ) {
        $meta_name = 'video_url';
        
        echo 'salvando VIDEO URL';
        
        // verify if this is an auto save routine. 
        // If it is our form has not been submitted, so we dont want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return;
    
        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times
    
        if ( !wp_verify_nonce( $_POST['metabox_video_url_noncename'], 'save_metabox_video_url' ) )
            return;
    
    
        // Check permissions
        if ( 'page' == $_POST['post_type'] ){
            if ( !current_user_can( 'edit_page', $post_id ) )
                return;
        }else{
            if ( !current_user_can( 'edit_post', $post_id ) )
                return;
        }
    
        // OK, we're authenticated: we need to find and save the data
    
        update_post_meta($post_id, $meta_name, trim($_POST[$meta_name]));
        
    }

}

SLUG::init();
