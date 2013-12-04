<?php
	include '../../../../../wp-config.php';
	
	$custom_theme = get_option("custom_theme");
	
	// custom blog background
	$bgColor = $custom_theme['background']['color'];
	$bgImage = $custom_theme['background']['url'];
	$bgPosition = $custom_theme['background']['position'];
	$bgRepeat = $custom_theme['background']['repeat'];
	$bgAttachment = $custom_theme['background']['attachment'];
	
	// custom blog header
	$headerMenuOpacity = $custom_theme['header']['menuOpacity'];
	$headerText = $custom_theme['header']['text'];
	$headerTextColor = $custom_theme['header']['textColor'];
	$headerLogo = $custom_theme['header']['logo'];
	$headerLogoPosition = $custom_theme['header']['logoPosition'];
	$headerColor = $custom_theme['header']['color'];
	$headerImage = $custom_theme['header']['image'];
	$headerImagePosition = $custom_theme['header']['imagePosition'];
	$headerImageRepeat = $custom_theme['header']['imageRepeat'];
	
	if( $headerColor != 'transparent' )
	{
		$headerColor = '#'.$headerColor;
	}
	
	// custom blog content
	$boxColor = $custom_theme['box']['color'];
	$boxTextColor = $custom_theme['box']['textColor'];
	$boxPattern = $custom_theme['box']['pattern'];
	$boxPatternOpacity = $custom_theme['box']['patternOpacity'];
	$linkColor = $custom_theme['content']['linkColor'];

	header("Content-Type: text/css");
?>

    /* general
    _________*/
    
    body{
        background:#<?php print $bgColor; ?> <?php if(!empty($bgImage)) : ?>url("<?php print $bgImage; ?>") <?php print $bgPosition .' '. $bgRepeat .' '. $bgAttachment; endif; ?>;
    }
    body a{
        color:#<?php print $linkColor; ?>;
    }
    
    /* Custom header 
    __________________*/
    
    #header{
        background:<?php echo $headerColor .' url(" '. $headerImage .'") '. $headerImagePosition .' '. $headerImageRepeat; ?>;
    }
    #header div.nav div.bg{
    	opacity:<?php print $headerMenuOpacity/100; ?>;
    }
    #header div.nav ul.nav li.back{
        background-color:#<?php print $linkColor; ?>;
        text-decoration:none;
    }
    #header div.nav ul a:hover{
    	text-decoration:none;
    }
    #header div.nav ul.nav li ul li a:hover{
        background-color:#<?php print $linkColor; ?>;
        color:#fff;
	}
    #header div.middle .title a{
    	<?php if(!empty($headerLogo)){ echo 'background: url("'. $headerLogo .'") '. $headerLogoPosition .' no-repeat;'; } ?>
        color:#<?php print $headerTextColor; ?>;
        font:normal 50px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
        text-decoration:none;
        text-indent:<?php print $headerText; ?>;
    }
    #header div.middle .title span.shadow{
    	<?php if( $headerText == '-999em' ) { echo 'display:none;'; } ?>
        color:#000;
        filter:alpha(opacity=30);
        font:normal 50px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
        opacity:0.2;
    }
    
    
    /* Custom colors
    __________________*/
    
    #content div.bigTitle{
        background-color:#<?php print $boxColor; ?>;
        margin:0;
        padding:0;
    }
    
    #content div.bigTitle h2
    , #content div.bigTitle h4{
        color:#<?php print $boxTextColor; ?>;
    }
    
    #content div.bigTitle div.previewPattern{
        background: url("<?php echo $boxPattern; ?>") repeat;
        filter: alpha(opacity=<?php print $boxPatterOpacity; ?>);
        height:36px;
        left:0;
        margin:0;
        opacity: <?php print $boxPatternOpacity/100; ?>;
        padding:0;
        position:absolute;
        top:0;
        width:100%;
    }
    
    div.widget_tag_cloud a:hover{
        background-color:#<?php print $linkColor; ?>;
        color:#ffffff;
        text-decoration:none;
    }
    
    #footer{
        background-color:#<?php print $boxColor; ?>;
        color:#<?php print $boxTextColor; ?>;
    }
    
    #footer a{
        color:#<?php print $boxTextColor; ?>;
    }
    
    #footer div.background{
        background:url("<?php echo $boxPattern; ?>") repeat;
        filter:alpha(opacity=<?php print $boxPatterOpacity; ?>);
        height:41px;
        left:0;
        margin:0;
        opacity:<?php print $boxPatternOpacity/100; ?>;
        padding:0;
        position:absolute;
        top:0;
        width:100%;
    }
