<?php

$options[] = array(	"type" => "maintabletop");

    /// General Settings
	
	    $options[] = array(	"name" => "General Settings",
						"type" => "heading");
						
		    $options[] = array(	"name" => "Theme Skin",
						        "toggle" => "true",
								"type" => "subheadingtop");
						
			$options[] = array(	"desc" => "Please select the CSS skin of your blog here.",
					                "id" => $shortname."_alt_stylesheet",
					                "std" => "Select a CSS skin:",
					                "type" => "select",
					                "options" => $alt_stylesheets);
						
			$options[] = array(	"type" => "subheadingbottom");
			
 
    /// Navigation Settings												
				
		$options[] = array(	"name" => "Navigation Settings",
						    "type" => "heading");
										
				$options[] = array(	"name" => "Exclude Pages from Header Menu",
								"toggle" => "true",
								"type" => "subheadingtop");
						
				$options[] = array(	"type" => "multihead");
						
				$options = pages_exclude($options);
									
				$options[] = array(	"type" => "subheadingbottom");
				
				
				
				$options[] = array(	"name" => "Include Categories from Header Menu Category",
						        "toggle" => "true",
								"type" => "subheadingtop");
						
				$options[] = array(	"label" => "Header Navigation Select Menu Category",
                	                "desc" => "Enter a comma-separated list of the <code>categories ID's</code> that you'd like to display in header (on the right). (ie. <code>1,2,3,4</code>)",
						            "id" => $shortname."_categories_id",
						            "std" => "",
						            "type" => "text");	
						
			$options[] = array(	"type" => "subheadingbottom");
				
			
$options[] = array(	"name" => "Footer Navigation",
						        "toggle" => "true",
								"type" => "subheadingtop");
						
				$options[] = array(	"label" => "Show breadcrumbs navigation bar",
                	                "desc" => "Enter a comma-separated list of the <code>page ID's</code> that you'd like to display in footer (on the right). (ie. <code>1,2,3,4</code>)",
						            "id" => $shortname."_footerpages",
						            "std" => "",
						            "type" => "text");	
						
			$options[] = array(	"type" => "subheadingbottom");
						
		$options[] = array(	"type" => "maintablebreak");
		
		
 
		$options[] = array(	"type" => "maintablebreak");
	$options[] = array(	"type" => "maintablebottom");

?>
