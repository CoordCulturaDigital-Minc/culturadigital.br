<?php
// XXX: hex / decimal operations notes needed
class color_scheme {
	var $name;
	var $colors = array();
	
	function color_scheme($name) {
	    $this->name = $name;
	}
	function add_color($hex_code) {
	    $this->colors[] = intval($hex_code, 16);
	}
	function add_colors($colors) {
	    foreach ($colors as $color) {
	    	$this->add_color($color);
	    }
	}
	function get_sorted_colors() {
	    sort($this->colors);
	    return $this->get_colors();
	}
	function get_colors() {
		$hex_colors = array();
	    foreach ($this->colors as $decimal) {
	    	$hex_colors[] = sprintf('%06X', $decimal);
	    }
	    return $hex_colors;
	}
}
class wp_option_choose_color_scheme extends wp_option {
	var $color_schemes = array();
	
	function add_color_scheme($color_scheme) {
	    $this->color_schemes[] = $color_scheme;
	}
	function add_color_schemes($color_schemes) {
	    foreach ($color_schemes as $color_scheme) {
	    	$this->add_color_scheme($color_scheme);
	    }
	}
	
	function render() {
		$html = '';
	    foreach ($this->color_schemes as $color_scheme) {
	    	$html .= '<div class="color-option">';
	    	$checked = '';
	    	if ($this->value==$color_scheme->name) {
	    		$checked = 'checked';
	    	}
	    	$html .= '<input type="radio" name="' . $this->name . '" ' . $checked . ' class="tog" value="' . $color_scheme->name . '" id="' . $color_scheme->name . '" />';
	    	$html .= '<table class="color-palette">';
	    	$html .= '<tr>';
	    	foreach ($color_scheme->get_colors() as $color) {
	    		$html .= '<td style="background: #' . $color . '">&nbsp;</td>';
	    	}
	    	$html .= '</tr>';
	    	$html .= '</table>';
	    	$html .= '</div>';
	    	$html .= '<label for="' . $color_scheme->name . '">' . $color_scheme->name . '</label>';
	    }
	    return wp_option::render($html);
	}
}
?>