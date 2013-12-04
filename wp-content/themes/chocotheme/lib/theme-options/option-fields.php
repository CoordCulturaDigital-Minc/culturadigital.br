<?php
define("INVALID_VALUE", -1);
define("VALID_VALUE", 1);
/*abstract*/ class base_wp_option {
    /* Name of the option, as it will be insrted in database. No funny charachters please! */
    var $name;
    
    /* Label of the option, as it will be shown on settings page */
    var $label;
    
    /* Type of the option - i.e. text option, checkbox option etc. Every option type is
       subclass of Wp_option class, and its name is "wp_option_{$option_type}" */
    var $type;
    
    /* Value of the option when the use hasn't setuped it yet.*/
    var $default_value;
    
    /* Value of the option */
    var $value;
    
    /* Hash that keeps key-value pairs of custom HTML attributes */
    var $html_attrs = array();
    
    /* Extra "help" text to be displayed under the field on the options's admin form. */
    var $help_text;
    
    var $validation_error;
    
    var $hidden = false;
    
    /*static*/ 
    function factory($opt_type, $name, $label=null) {
        $class_name = "Wp_option_$opt_type";
        if (!class_exists($class_name)) {
            trigger_error("Cannot create option from type $opt_type -- unknown type", E_USER_ERROR);
        }
        
        if (empty($name)) {
            trigger_error("Cannot create option without name", E_USER_ERROR);
        }
        if (preg_match('~[^a-zA-Z0-9_]~', $name)) {
            trigger_error("Option name can include only latin letters, numbers, dashes and underscores, 
                    \"$name\" is not valid option name", E_USER_ERROR);
        }
        
        if (is_null($label)) {
            $label = ucwords(str_replace(array('_', '-'), ' ', $name));
        }
        
        return new $class_name($name, $label);
    }
    
    /* Cosntructor. Do not override, use init() instead */ 
    /* final */ function base_wp_option($name, $label) {
        $this->name = $name;
        $this->label = $label;
        $this->init();
        if (defined('WP_ADMIN')) {
            $this->admin_init();
        }
    }
    
    /* Implement this in child classes if you need to do some initialization.
       This will be called immediatlly after the constructor */
    function init() {}
    /* Same as above, but for amin */
    function admin_init() {}
    
    /* Collects information from input and setups object value */
    /*public*/ function set_value_from_input() {
        $this->set_value($_REQUEST[$this->name]);
    }
    
    /* This needs to be implemented in every child class */
    /*abstract*/ function render() {}
    
    function set_value($value) {
        $this->value = $value;
    }
    
    /* You can setup HTML tag attributes via this field. Pass them in hash array where key is the attribute name
       and value is attribute value, i.e.:
       array(
           'maxlength'=>'32',
           'style'=>'width: 180px;'
       )
     */
    function set_html_attr($attrs) {
        $this->html_attrs = $attrs;
    }
    function get_custom_attrs() {
        $html_attrs = array();
        foreach ($this->html_attrs as $key => $value) {
            $html_attrs[] = $key . '="' . $value . '"';
        }
        return implode(' ', $html_attrs);
    }
    function help_text($help_text) {
        $this->help_text = $help_text;
    }
    
    function get_error() {
        return $this->validation_error;
    }
    
    function hide() {
        $this->hidden = true;
    }

}
// Base class for all options that use wordpress interface for managing options via 
// add_option / update_option / get_option

/*abstract*/ class wp_option extends base_wp_option {
    function init() {
        $this->set_value(get_option($this->name));
    }
    function admin_init() {
        if (isset($_GET['delete']) && $_GET['delete'] == $this->name) {
            $this->reset_value();
        }
    }
    function render($field_html, $colspan = false) {
        $html = '
            <tr valign="top" class="' . get_class($this) . ' field-' . $this->name . '" ' . ($this->hidden ? 'style="display: none; "' : '') . '>
                <th scope="row" class="field-label"><label for="' . $this->name . '">' . $this->label . '</label></th>
                <td class="field">' . $field_html . ' <span class="description">' . $this->help_text . '</span></td>
            </tr>
        ';
        return $html;
    }
    function save() {
        $this->value = get_magic_quotes_gpc() ? stripslashes($this->value) : $this->value;
        update_option($this->name, $this->value);
    }
    
    function set_default_value($default_vallue) {
        $current_value = get_option($this->name);
        if ($current_value) {
            return;
        }
        $this->default_value = $default_vallue;
        add_option($this->name, $default_vallue);
        $this->set_value($default_vallue);
    }
    
    function reset_value() {
        $this->value = $this->default_value;
        $this->save();
    }
    
    function add_to_get($key, $val) {
        return '?' . preg_replace('~&?' . preg_quote($key) . '(=|$|&)([^&]*)?~', '', $_SERVER['QUERY_STRING']) . '&' . $key . '=' . $val;
    }
    
    function get_reset_link() {
        return $this->add_to_get('delete', $this->name);
    }
}

// Most basic option -- single line text
class wp_option_text extends wp_option {
    function init() {
        wp_option::init();
        $this->html_attrs = array_merge(
            array('class'=>'regular-text'),
            $this->html_attrs
        );
    }
    function render() {
        $field_html = '<input type="text" name="' . $this->name . '" value="' . $this->value . '" id="' . $this->name . '" ' . $this->get_custom_attrs() . ' />';
        return wp_option::render($field_html);
    }
}
class wp_option_int extends wp_option_text {
	function set_value_from_input() {
	    $val = $_REQUEST[$this->name];
	    if (!is_numeric($val)) {
	    	$this->validation_error = "should be integer";
	    	return INVALID_VALUE;
	    }
	    $this->set_value($val);
	}
}
// 
class wp_option_textarea extends wp_option {
    function admin_init() {
        wp_option::admin_init();
        $this->help_text($this->help_text);
        $this->html_attrs = array_merge(
            array(
                'class'=>'regular-text',
                'rows'=>'5',
                /**/
                'style'=>'width: 500px', 
            ),
            $this->html_attrs
        );
    }
    function help_text($help_text) {
        $this->help_text = "<br />$help_text";
    }
    function render() {
        $field_html = '<textarea name="' . $this->name . '" id="' . $this->name . '" ' . $this->get_custom_attrs() . '>' . $this->value . '</textarea>';
        return wp_option::render($field_html);
    }
}

class wp_option_header_scripts extends wp_option_textarea {
    var $help_text = 'If you need to add scripts to your header, you should enter them in this box.';
    
    function init() {
        wp_option_textarea::init();
        add_action('wp_head', array($this, 'print_the_code'));
    }
    
    function print_the_code() {
        echo get_option($this->name);
    }
}

class wp_option_footer_scripts extends wp_option_textarea {
    var $help_text = 'If you need to add scripts to your footer (like Google Analytics tracking code), you should enter them in this box.';
    function init() {
        wp_option_textarea::init();
        add_action('wp_footer', array($this, 'print_the_code'));
    }
    function print_the_code() {
        echo get_option($this->name);
    }
}

/*  */
class wp_option_choose_category extends wp_option {
    function render() {
        $dropdown_html = wp_dropdown_categories(array(
            'name'=>$this->name,
            'echo'=>false,
            'hide_empty'=>false,
            'hierarchical'=>1,
            'exclude'=>1, // Exclude uncategorized
            'selected'=>$this->value,
            'show_option_none'=>'Please Choose',
        ));
        return wp_option::render($dropdown_html);
    }
}
class wp_option_choose_page extends wp_option {
    function render() {
        $dropdown_html = wp_dropdown_pages(array(
            'name'=>$this->name,
            'echo'=>false,
            'hierarchical'=>1,
            'selected'=>$this->value,
            'show_option_none'=>'Please Choose',
        ));
        return wp_option::render($dropdown_html);
    }
}
/*  */
class wp_option_select extends wp_option {
    var $opts = array();
    function add_options($opts) {
        $this->opts = $opts;
    }
    function render() {
        $html = '<select name="'  . $this->name . '">';
        foreach ($this->opts as $key => $value) {
            $selected = '';
            if ($key==$this->value) {
                $selected = 'selected="selected"';
            }
            $html .= "\n";
            $html .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
        }
        $html .= '</select>';
        return wp_option::render($html);
    }
}
/**
* 
*/
class wp_option_file extends wp_option {
    /**
     * http://xref.limb-project.com/tests_runner/lib/spikephpcoverage/src/util/Utility.php.source.txt
     * 
     * Make directory recursively.
     * (Taken from: http://aidan.dotgeek.org/lib/?file=function.mkdirr.php)
     *
     * @param $dir Directory path to create
     * @param $mode=0755
     * @return True on success, False on failure
     * @access protected
     */
    function mkdir_recursive($dir, $mode=0755) {
        // Check if directory already exists
        if (is_dir($dir) || empty($dir)) {
            return true;
        }
        // Crawl up the directory tree
        $next_pathname = substr($dir, 0, strrpos($dir, DIRECTORY_SEPARATOR));
        if ($this->mkdir_recursive($next_pathname, $mode)) {
            if (!file_exists($dir)) {
                return mkdir($dir, $mode);
            }
        }
    
        return false;
    }
    function render() {
        $html = '<input type="file" name="' . $this->name . '" />';
        if ($this->value) {
            $html .= "&nbsp;Current File: <a href=" . get_option('upload_path') . $this->value . "'>Download</a>";
            $html .= "&nbsp;|&nbsp;<a href='".$this->get_reset_link()."'>Delete</a>";
        }
        return wp_option::render($html);
    }
    function get_file_extension($filename) {
        $ext = preg_replace('~.*\.~', '', $filename);
        return $ext;
    }
    function validate_file() {
        return VALID_VALUE;
    }
    function set_value_from_input() {
        if (empty($_FILES) || !is_uploaded_file($_FILES[$this->name]['tmp_name'])) {
            return;
        }
        $valid = $this->validate_file();
        if ($valid == INVALID_VALUE) {
            return $valid;
        }
        $upload_location = wp_upload_dir();
        $upload_dir = $upload_location['path'];
        
        $filename = preg_replace('~[^\w\.]~', '', $_FILES[$this->name]['name']);
        
        $destination = $upload_dir . '/' . $filename;
        
        $filename_ch = 1;
        while (file_exists($destination)) {
            $destination = $upload_dir . '/' . $filename_ch . '-' . $filename;
            $filename_ch++;
        }
        
        if (copy($_FILES[$this->name]['tmp_name'], $destination)) {
            if ($this->value && file_exists($upload_dir . $this->value)) {
                unlink($upload_dir . $this->value);
            }
            $this->value = $upload_location['url'] . '/' . $filename;
            return VALID_VALUE;
        } else {
            $this->validation_error = "Error occured while writing a file. Please check whether " . $upload_dir . " is a writable directory.";
            return INVALID_VALUE;
        }
    }
}

// Emulate static propertie in PHP4
$wp_option_image__stylesheet_printed = false;

class wp_option_image extends wp_option_file {
    function _print_stylesheet() {
        global $wp_option_image__stylesheet_printed;
        if ($wp_option_image__stylesheet_printed) {
            return;
        }
        echo '<link rel="stylesheet" href="'. get_option('home') . '/wp-includes/js/thickbox/thickbox.css" type="text/css" media="screen" title="no title" charset="utf-8" />';
        $wp_option_image__stylesheet_printed = true;
    }
    function admin_init() {
        add_action('admin_head', array($this, '_print_stylesheet'));
        wp_enqueue_script('thickbox');
        wp_option_file::admin_init();
    }
    function render() {
        $html = '<input type="file" name="' . $this->name . '" />';
        if ($this->value) {
            $html .= "&nbsp;<a href='".$this->value."?TB_width=800' class='thickbox'>View Current Image</a>";
            $html .= "&nbsp;|&nbsp;<a href='".$this->get_reset_link()."'>Delete</a>";
        }
        return wp_option::render($html);
    }
    function validate_file() {
        $valid = getimagesize($_FILES[$this->name]['tmp_name']);
        if (!$valid) {
            $this->validation_error = "The uploaded filetype is invalid (".$this->get_file_extension($_FILES[$this->name]['name']).").";
            return INVALID_VALUE;
        }
        return VALID_VALUE;
    }
}

$___tiny_mce_included = false;
$___tiny_mce_loaded = false;
class wp_option_rich_text extends wp_option_textarea {
    function include_js() {
        global $___tiny_mce_included;
        if ($___tiny_mce_included) {
            return;
        }
        $___tiny_mce_included = true;
        echo '<script src="' . get_option('home') . '/wp-includes/js/tinymce/tiny_mce.js" type="text/javascript" charset="utf-8"></script>';
    }
    function admin_init() {
        add_action('admin_head', array($this, 'include_js'));
        wp_option_textarea::admin_init();
    }
    
    function render() {
        global $___tiny_mce_loaded;
        ob_start();
        include('rich-text-field.php');
        $field_html = ob_get_contents();
        ob_end_clean();
        $___tiny_mce_loaded = true;
        return wp_option::render($field_html);
    }
}

class wp_option_separator extends wp_option {
    function render() {
        $html = '
            <tr valign="top">
                <th colspan="2" scope="row" class="field-label"><h3>' . $this->label . '</h3></th>
            </tr>
        ';
        return $html;
    }
    function set_value_from_input() {
        ;
    }
}

class wp_option_img_url extends wp_option_text {
    function render() {
        $field_html =
            '<input type="text" name="' . $this->name . '" value="' . $this->value . '" id="' . $this->name . '" ' . $this->get_custom_attrs() . ' />' . 
            '<a onclick="return false;" title="Add an Image" class="thickbox" style="text-decoration: none" id="add_image" href="media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true&amp;width=640&amp;height=464"><img alt="Add an Image" src="images/media-button-image.gif"/> Open Media Gallery</a>';
        return wp_option::render($field_html);
    }
}

$wp_option_set__sort_js_printed = false;
class wp_option_set extends wp_option_text {
    var $choices = array();
    var $sortable = false;
    
    function render() {
        if (!is_array($this->value) || empty($this->value)) {
            $this->value = array();
            $current_values = array();
        } else {
            $current_values = array_combine($this->value, $this->value);
        }
        if ($this->sortable) {
            $html = $this->render_as_sortable($current_values);
            return wp_option::render($html);
        }
        
        ob_start();
        ?>
        <?php $loopID = 0; foreach ($this->choices as $key => $label) : ?>
            <input type="checkbox" name="<?php echo $this->name?>[]" <?php echo (isset($current_values[$key])) ? 'checked' : '';?> value="<?php echo $key?>" id="<?php echo $this->name?>_<?php echo $loopID?>" />
            <label for="<?php echo $this->name?>_<?php echo $loopID?>"><?php echo $label?></label>
            <br />
        <?php $loopID ++; endforeach; ?>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return wp_option::render($html);
    }
    
    function render_sort_js() {
        global $wp_option_set__sort_js_printed;
        if ($wp_option_set__sort_js_printed == true) {
            return;
        }
        $wp_option_set__sort_js_printed = true;
        ?>
        <script type="text/javascript" charset="utf-8">
        (function ($) {
            $('.sort-line .move-up').live('click', function () {
                var holder = $(this).parents('.sort-line');
                var move_data = $(holder).find('.sort-data .chunk').remove();
                var moved_data = $(holder).prev().find('.sort-data .chunk').remove();
                $(holder).prev().find('.sort-data').append(move_data);
                $(holder).find('.sort-data').append(moved_data);
                
                return false;
            });
            
            $('.sort-line .move-down').live('click', function () {
                var holder = $(this).parents('.sort-line');
                var move_data = $(holder).find('.sort-data .chunk').remove();
                var moved_data = $(holder).next().find('.sort-data .chunk').remove();
                $(holder).next().find('.sort-data').append(move_data);
                $(holder).find('.sort-data').append(moved_data);
                
                return false;
            });
        })(jQuery)
        </script>
        <?php
    }
    
    /* NOTE: In order sorting to work you need to use get_pages() + foreach because wp_list_pages() ignores the order you input IDs in the include= clause */
    function render_as_sortable($current_values) {
        $nice_choices = array();
        if (!empty($current_values)) {
            $sliced_choices = array_flip($this->choices);
            foreach ($current_values as $val) {
                $ind = array_search($val, array_values($sliced_choices));
                if (!($ind === FALSE)) {
                    $nice_choices[$val] = $this->choices[$val];
                    if ($ind != 0) {
                        $sliced_choices = array_slice($sliced_choices, 0, $ind) + array_slice($sliced_choices, $ind + 1);
                    } else {
                        $sliced_choices = array_slice($sliced_choices, 1);
                    }
                    
                }
            }
            $sliced_choices = array_flip($sliced_choices);
            $nice_choices = $nice_choices + $sliced_choices;
        } else {
            $nice_choices = $this->choices;
        }
        
        ob_start();
        ?>
        <?php $loopID = 0; foreach ($nice_choices as $key => $label) : ?>
        <div class="sort-line">
            <div style="display: inline; float: left; width: 40px;">
            <?php if ($loopID > 0) : ?>
                <a href="#" class="move-up" style="display: block; float: left; width: 10px; margin-right: 5px; text-indent: -4000px; font-size: 0px; background: url(<?php bloginfo('stylesheet_directory'); ?>/lib/images/arrow-up.gif) no-repeat 0 5px;">Up</a>
            <?php endif; ?>
            <?php if ($loopID < count($this->choices) - 1) : ?>
                <a href="#" class="move-down" style="display: block; float: left; width: 10px; text-indent: -4000px; font-size: 0px; background: url(<?php bloginfo('stylesheet_directory'); ?>/lib/images/arrow-down.gif) no-repeat 0 5px; <?php echo $loopID==0 ? 'margin-left: 15px;' : '' ?>">Down</a>
            <?php endif; ?>
            </div>
            <div class="sort-data" style="display: inline; float: left;">
                <div class="chunk">
                    <input type="checkbox" name="<?php echo $this->name?>[]" <?php echo (isset($current_values[$key])) ? 'checked' : '';?> value="<?php echo $key?>" id="<?php echo $this->name?>_<?php echo $loopID?>" />
                    <label for="<?php echo $this->name?>_<?php echo $loopID?>"><?php echo $label?></label>
                </div>
            </div>
            <div class="clear" style="height: 0px; line-height: 0px; font-size: 0px;">&nbsp;</div>
        </div>
        <?php $loopID ++; endforeach; ?>
        <?php $this->render_sort_js(); ?>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
    
    function add_choices($array) {
        $this->choices = $array;
    }
    
    function save() {
        update_option($this->name, $this->value);
    }
    
    function create_sortable() {
        $this->sortable = true;
        return $this;
    }
}

class wp_option_choose_pages extends wp_option_set {
    function init() {
        $raw_pages = get_pages('child_of=0&parent=0');
        $nice_pages = array();
        
        $raw_pages = is_array($raw_pages) ? $raw_pages : array();
        
        foreach ($raw_pages as $p) {
            $nice_pages[$p->ID] = $p->post_title;
        }
        $this->add_choices($nice_pages);
        wp_option_set::init();
    }
}
class wp_option_choose_categories extends wp_option_set {
    function init() {
        $raw_cats = get_categories();
        $nice_cats = array();
        foreach ($raw_cats as $c) {
            $nice_cats[$c->term_id] = $c->name;
        }
        $this->add_choices($nice_cats);
        wp_option_set::init();
    }
}
class wp_option_choose_links_category extends wp_option_set {
    function init() {
        
    }
}

class wp_option_color extends wp_option_text {
    var $html_class_name;
    function admin_init() {
        $token = wp_create_nonce(mt_rand());
        $this->html_class_name = "colorpicker_$token";
        
        $handle = 'jq_colorpicker';
        $js_src = get_bloginfo('stylesheet_directory') . '/lib/theme-options/colorpicker/colorpicker.js';
        $css_src = get_bloginfo('stylesheet_directory') . '/lib/theme-options/colorpicker/colorpicker.css';
        $deps = array('jquery');
        wp_enqueue_script($handle, $js_src, $deps);
        wp_enqueue_style( $handle, $css_src);
        
        // Append additional class name to the input
        $current_class = '';
        if (isset($this->html_attrs['class'])) {
            $current_class = $this->html_attrs['class'];
        }
        $new_class = "$current_class $this->html_class_name alignleft";
        $this->html_attrs['class'] = $new_class;
        
        wp_option_text::admin_init();
        add_action('admin_footer', array($this, 'print_js'));
    }
    function print_js() {
        ?>
        <script type="text/javascript" charset="utf-8">
            jQuery(function ($) {
                $('.color-preview').click(function () {
                    $(this).prev().click();
                })
                $('.<?php echo $this->html_class_name ?>').ColorPicker({
                    onChange: function (e, hex) {
                        $('.<?php echo $this->html_class_name ?>').val('#' + hex);
                        $('.<?php echo $this->html_class_name ?>').next().css('background', '#' + hex);
                    },
                    onSubmit: function(hsb, hex, rgb, el) {
                        $(el).ColorPickerHide();
                    },
                    color: '<?php echo $this->value ?>',
                });
            });
        </script>
        <?php
    }
    function set_value_from_input() {
        $color = $_REQUEST[$this->name];
        $val = preg_match('~^#~', $color) ? $color : "#$color";
        $this->set_value($val);
    }
    function render() {
        $field_html = '<input type="text" name="' . $this->name . '" value="' . $this->value . '" id="' . $this->name . '" ' . $this->get_custom_attrs() . ' /><span style="background: ' . $this->value . '; width: 22px; float: left; margin: 2px 4px;" class="color-preview">&nbsp;</span>';
        return wp_option::render($field_html);
    }
}
include_once("choose-color-scheme.php");
?>