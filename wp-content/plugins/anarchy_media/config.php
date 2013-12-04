<?php

if (defined('POC_CACHE')) {

if( !defined('POC_CACHE_4') ) {
require_once(ABSPATH .'wp-content/plugins/plugin-output-cache/poc-cache.php');
}
$key_amp = 'wpamp';
$data_amp = poc_cache_fetch($key_amp);
if($data_amp) { extract( unserialize($data_amp) ); }
else {

/*************************************************************************
* Configure default Anarchy options
**************************************************************************/

// Hex colours (minus the #) and CSS styles for the MP3 Flash Player 
$playerbgD ='DDDDDD';				// Background colour
$playerleftbgD = 'BBBBBB';			// Left background colour
$playerrightbgD = 'BBBBBB';			// Right background colour
$playerrightbghoverD = '666666';	// Right background colour (hover)
$playerlefticonD = '000000';		// Left icon colour
$playerrighticonD = '000000';		// Right icon colour
$playerrighticonhoverD = 'FFFFFF';	// Right icon colour (hover)
$playertextD = '333333';			// Text colour
$playersliderD = '666666';			// Slider colour
$playertrackD = '999999';			// Loader bar colour
$playerloaderD = '666666';			// Progress track colour
$playerborderD = '333333';			// Progress track border colour
$mp3playerstyleD = 'vertical-align:bottom; margin:10px 0 5px 2px;';	// Flash mp3 player css style
$mp3imgmarginD = '0.5em 0.5em -4px 5px';	// Mp3 button image css margins

// Flash video player options
$flvwidthD = '400'; 		// Width of the flv player
$flvheightD = '320';		// Height of the flv player (allow 20px for controller)

// Quicktime player options
$qtwidthD = '400';		// Width of your Quicktime player
$qtheightD = '316';		// Height of your Quicktime player (allow 16px for controller)
$qtversionD = '6';		// Set the minimum Quicktime version for your site.
$vidimgmarginD = '0';	// QT poster image placeholder css margins

// WMV player options
$wmvwidthD = '400';		// Width of your WMV player - ignored by WinIE
$wmvheightD = '372';		// Height of your WMV player (allow 46px for WMV controller) - ignored by WinIE

// YouTube player options
$youtubewidthD = '425';		// Width of your YouTube player
$youtubeheightD = '344';		// Height of your YouTube player

// Google Video options
$googlewidthD = '400';		// Width of your Google player
$googleheightD = '326';		// Height of your Google player

// iFilm options
$ifilmwidthD ='448';			// Width of your iFilm player
$ifilmheightD = '365';		// Height of your iFilm player

// DailyMotion options
$dailymotionwidthD ='425';	// Width of your DailyMotion player
$dailymotionheightD = '335';	// Height of your DailyMotion player

// Metacafe options
$metacafewidthD ='400';		// Width of your Metacafe player
$metacafeheightD = '345';	// Height of your Metacafe player

// Myspace options
$myspacewidthD ='425';		// Width of your Myspace player
$myspaceheightD = '360';		// Height of your Myspace player

// Break options
$breakwidthD ='464';		// Width of your Break player
$breakheightD = '392';		// Height of your Break player

// Revver options
$revverwidthD = '480';
$revverheightD = '376';
$revverIDD = '44205';

/**********************************************************************
*   End of configurable plugin options
**********************************************************************/
// General site options
$anarchy_url = get_settings('siteurl').'/wp-content/plugins/anarchy_media';
if(get_option('anarchy_accepted_domains1')) : $accepted_domains1 = get_option('anarchy_accepted_domains1');
else :
$accepted_domains1 = '';
endif;
if(get_option('anarchy_accepted_domains2')) : $accepted_domains2 = get_option('anarchy_accepted_domains2');
else :
$accepted_domains2 = '';
endif;
if(get_option('anarchy_accepted_domains3')) : $accepted_domains3 = get_option('anarchy_accepted_domains3');
else :
$accepted_domains3 = '';
endif;
if($accepted_domains2 !== '') $domain2 = ',"'. $accepted_domains2 .'"';
if($accepted_domains3 !== '') $domain3 = ',"'. $accepted_domains3 .'"';
$accepted_domains = '("'. $accepted_domains1.'"'. $domain2 . $domain3 .')';

if(get_option('anarchy_viddownloadLink')) : $viddownloadLink = get_option('anarchy_viddownloadLink');
else : $viddownloadLink = 'none';
endif;

if(get_option('anarchy_autohyperlinks')) : $autohyperlinks = get_option('anarchy_autohyperlinks');
else : $autohyperlinks = 'false';
endif;

// RTE buttons
if(get_option('anarchy_flashbutton') && (get_option('anarchy_flashbutton') == 'false')) : $flashbutton = 'false';
else : $flashbutton = 'true';
endif;

if(get_option('anarchy_directorbutton')) : $directorbutton = get_option('anarchy_directorbutton');
else : $directorbutton = 'false';
endif;

if(get_option('anarchy_videobutton') && (get_option('anarchy_videobutton') == 'false')) : $videobutton = 'false';
else : $videobutton = 'true';
endif;

// MP3 Flash player options
if(get_option('anarchy_playerloop')) : $playerloop = get_option('anarchy_playerloop');
else : $playerloop = 'no';
endif;

if(get_option('anarchy_mp3downloadLink')) : $mp3downloadLink = get_option('anarchy_mp3downloadLink');
else : $mp3downloadLink = 'none';
endif;

if(get_option('anarchy_mp3imgmargin')) : $mp3imgmargin = get_option('anarchy_mp3imgmargin');
else : $mp3imgmargin = $mp3imgmarginD; 
endif;

if(get_option('anarchy_mp3playerstyle')) : $mp3playerstyle = get_option('anarchy_mp3playerstyle');
else : $mp3playerstyle = $mp3playerstyleD; 
endif;

// Hex colours for the MP3 Flash Player (minus the #)
if(get_option('anarchy_playerbg')) : $playerbg = get_option('anarchy_playerbg');
else : $playerbg = $playerbgD; 
endif;

if(get_option('anarchy_playerleftbg')) : $playerleftbg = get_option('anarchy_playerleftbg');
else : $playerleftbg = $playerleftbgD; 
endif;

if(get_option('anarchy_playerrightbg')) : $playerrightbg = get_option('anarchy_playerrightbg');
else : $playerrightbg = $playerrightbgD; 
endif;

if(get_option('anarchy_playerrightbghover')) : $playerrightbghover = get_option('anarchy_playerrightbghover');
else : $playerrightbghover = $playerrightbghoverD; 
endif;

if(get_option('anarchy_playerlefticon')) : $playerlefticon = get_option('anarchy_playerlefticon');
else : $playerlefticon = $playerlefticonD; 
endif;

if(get_option('anarchy_playerrighticon')) : $playerrighticon = get_option('anarchy_playerrighticon');
else : $playerrighticon = $playerrighticonD; 
endif;

if(get_option('anarchy_playerrighticonhover')) : $playerrighticonhover = get_option('anarchy_playerrighticonhover');
else : $playerrighticonhover = $playerrighticonhoverD; 
endif;

if(get_option('anarchy_playertext')) : $playertext = get_option('anarchy_playertext');
else : $playertext = $playertextD; 
endif;

if(get_option('anarchy_playerslider')) : $playerslider = get_option('anarchy_playerslider');
else : $playerslider = $playersliderD; 
endif;

if(get_option('anarchy_playertrack')) : $playertrack = get_option('anarchy_playertrack');
else : $playertrack = $playertrackD; 
endif;

if(get_option('anarchy_playerloader')) : $playerloader = get_option('anarchy_playerloader');
else : $playerloader = $playerloaderD; 
endif;

if(get_option('anarchy_playerborder')) : $playerborder = get_option('anarchy_playerborder');
else : $playerborder = $playerborderD; 
endif;

// Flash video player options
if(get_option('anarchy_flvwidth')) : $flvwidth = get_option('anarchy_flvwidth');
else : $flvwidth = $flvwidthD; 
endif;

if(get_option('anarchy_flvheight')) : $flvheight = get_option('anarchy_flvheight');
else : $flvheight = $flvheightD;
endif;

if(get_option('anarchy_flvfullscreen')) : $flvfullscreen = get_option('anarchy_flvfullscreen');
else : $flvfullscreen = 'true';
endif;

// Quicktime player options
if(get_option('anarchy_qtwidth')) : $qtwidth = get_option('anarchy_qtwidth');
else : $qtwidth = $qtwidthD; 
endif;
if(get_option('anarchy_qtheight')) : $qtheight = get_option('anarchy_qtheight');
else : $qtheight = $qtheightD;
endif;
if(get_option('anarchy_qtversion')) : $qtversion = get_option('anarchy_qtversion');
else : $qtversion = $qtversionD;
endif;

if(get_option('anarchy_qtloop')) : $qtloop = get_option('anarchy_qtloop');
else : $qtloop = 'false';
endif;

if(get_option('anarchy_qtkiosk')) : $qtkiosk = get_option('anarchy_qtkiosk');
else : $qtkiosk = 'true';
endif;

if(get_option('anarchy_vidimgmargin')) : $vidimgmargin = get_option('anarchy_vidimgmargin');
else : $vidimgmargin = $vidimgmarginD; 
endif;

// WMV player options
if(get_option('anarchy_wmvwidth')) : $wmvwidth = get_option('anarchy_wmvwidth');
else : $wmvwidth = $wmvwidthD; 
endif;
if(get_option('anarchy_wmvheight')) : $wmvheight = get_option('anarchy_wmvheight');
else : $wmvheight = $wmvheightD;
endif;

// YouTube player options
if(get_option('anarchy_youtubewidth')) : $youtubewidth = get_option('anarchy_youtubewidth');
else : $youtubewidth = $youtubewidthD; 
endif;

if(get_option('anarchy_youtubeheight')) : $youtubeheight = get_option('anarchy_youtubeheight');
else : $youtubeheight = $youtubeheightD;
endif;

// Google Video options
if(get_option('anarchy_googlewidth')) : $googlewidth = get_option('anarchy_googlewidth');
else : $googlewidth = $googlewidthD; 
endif;

if(get_option('anarchy_googleheight')) : $googleheight = get_option('anarchy_googleheight');
else : $googleheight = $googleheightD;
endif;

// iFilm options
if(get_option('anarchy_ifilmwidth')) : $ifilmwidth = get_option('anarchy_ifilmwidth');
else : $ifilmwidth = $ifilmwidthD; 
endif;

if(get_option('anarchy_ifilmheight')) : $ifilmheight = get_option('anarchy_ifilmheight');
else : $ifilmheight = $ifilmheightD;
endif;

// DailyMotion options
if(get_option('anarchy_dailymotionwidth')) : $dailymotionwidth = get_option('anarchy_dailymotionwidth');
else : $dailymotionwidth = $dailymotionwidthD; 
endif;

if(get_option('anarchy_dailymotionheight')) : $dailymotionheight = get_option('anarchy_dailymotionheight');
else : $dailymotionheight = $dailymotionheightD;
endif;

// Metacafe options
if(get_option('anarchy_metacafewidth')) : $metacafewidth = get_option('anarchy_metacafewidth');
else : $metacafewidth = $metacafewidthD; 
endif;

if(get_option('anarchy_metacafeheight')) : $metacafeheight = get_option('anarchy_metacafeheight');
else : $metacafeheight = $metacafeheightD;
endif;

// Myspace options
if(get_option('anarchy_myspacewidth')) : $myspacewidth = get_option('anarchy_myspacewidth');
else : $myspacewidth = $myspacewidthD; 
endif;

if(get_option('anarchy_myspaceheight')) : $myspaceheight = get_option('anarchy_myspaceheight');
else : $myspaceheight = $myspaceheightD;
endif;

// Break options
if(get_option('anarchy_breakwidth')) : $breakwidth = get_option('anarchy_breakwidth');
else : $breakwidth = $breakwidthD; 
endif;

if(get_option('anarchy_breakheight')) : $breakheight = get_option('anarchy_breakheight');
else : $breakheight = $breakheightD;
endif;

// Revver options
if(get_option('anarchy_revverwidth')) : $revverwidth = get_option('anarchy_revverwidth');
else : $revverwidth = $revverwidthD; 
endif;

if(get_option('anarchy_revverheight')) : $revverheight = get_option('anarchy_revverheight');
else : $revverheight = $revverheightD;
endif;

if(get_option('anarchy_revverID')) : $revverID = get_option('anarchy_revverID');
else : $revverID = $revverIDD; 
endif;


/*************************************************************************
* End Configure Anarchy options
**************************************************************************/

$result_amp = array(
"anarchy_url" => $anarchy_url,
"accepted_domains1" => $accepted_domains1,
"accepted_domains2" => $accepted_domains2,
"accepted_domains3" => $accepted_domains3,
"domain2" => $domain2,
"domain3" => $domains3,
"accepted_domains" => $accepted_domains,
"viddownloadLink" => $viddownloadLink,
"autohyperlinks" => $autohyperlinks,
"flashbutton" => $flashbutton,
"directorbutton" => $directorbutton,
"videobutton" => $videobutton,
"wysiwyg" => $wysiwyg,
"playerloop" => $playerloop,
"mp3downloadLink" => $mp3downloadLink,
"mp3imgmargin" => $mp3imgmargin,
"mp3playerstyle" => $mp3playerstyle,
"playerbg" => $playerbg,
"playerleftbg" => $playerleftbg,
"playerrightbg" => $playerrightbg,
"playerrightbghover" => $playerrightbghover,
"playerlefticon" => $playerlefticon,
"playerrighticon" => $playerrighticon,
"playerrighticonhover" => $playerrighticonhover,
"playertext" => $playertext,
"playerslider" => $playerslider,
"playertrack" => $playertrack,
"playerloader" => $playerloader,
"playerborder" => $playerborder,
"flvwidth" => $flvwidth,
"flvheight" => $flvheight,
"flvfullscreen" => $flvfullscreen,
"qtwidth" => $qtwidth,
"qtheight" => $qtheight,
"qtversion" => $qtversion,
"qtloop" => $qtloop,
"qtkiosk" => $qtkiosk,
"vidimgmargin" => $vidimgmargin,
"wmvwidth" => $wmvwidth,
"wmvheight" => $wmvheight,
"youtubewidth" => $youtubewidth,
"youtubeheight" => $youtubeheight,
"googlewidth" => $googlewidth,
"googleheight" => $googleheight,
"ifilmwidth" => $ifilmwidth,
"ifilmheight" => $ifilmheight,
"dailymotionwidth" => $dailymotionwidth,
"dailymotionheight" => $dailymotionheight,
"metacafewidth" => $metacafewidth,
"metacafeheight" => $metacafeheight,
"myspacewidth" => $myspacewidth,
"myspaceheight" => $myspaceheight,
"breakwidth" => $breakwidth,
"breakheight" => $breakheight,
"revverwidth" => $revverwidth,
"revverheight" => $revverheight,
"revverID" => $revverID
);

poc_cache_store( $key_amp, serialize($result_amp) );
}
}
else {
/*************************************************************************
* Configure default Anarchy options
**************************************************************************/

// Hex colours (minus the #) and CSS styles for the MP3 Flash Player 
$playerbgD ='DDDDDD';				// Background colour
$playerleftbgD = 'BBBBBB';			// Left background colour
$playerrightbgD = 'BBBBBB';			// Right background colour
$playerrightbghoverD = '666666';	// Right background colour (hover)
$playerlefticonD = '000000';		// Left icon colour
$playerrighticonD = '000000';		// Right icon colour
$playerrighticonhoverD = 'FFFFFF';	// Right icon colour (hover)
$playertextD = '333333';			// Text colour
$playersliderD = '666666';			// Slider colour
$playertrackD = '999999';			// Loader bar colour
$playerloaderD = '666666';			// Progress track colour
$playerborderD = '333333';			// Progress track border colour
$mp3playerstyleD = 'vertical-align:bottom; margin:10px 0 5px 2px;';	// Flash mp3 player css style
$mp3imgmarginD = '0.5em 0.5em -4px 5px';	// Mp3 button image css margins

// Flash video player options
$flvwidthD = '400'; 		// Width of the flv player
$flvheightD = '320';		// Height of the flv player (allow 20px for controller)

// Quicktime player options
$qtwidthD = '400';		// Width of your Quicktime player
$qtheightD = '316';		// Height of your Quicktime player (allow 16px for controller)
$qtversionD = '6';		// Set the minimum Quicktime version for your site.
$vidimgmarginD = '0';	// QT poster image placeholder css margins

// WMV player options
$wmvwidthD = '400';		// Width of your WMV player - ignored by WinIE
$wmvheightD = '372';		// Height of your WMV player (allow 46px for WMV controller) - ignored by WinIE

// YouTube player options
$youtubewidthD = '425';		// Width of your YouTube player
$youtubeheightD = '344';		// Height of your YouTube player

// Google Video options
$googlewidthD = '400';		// Width of your Google player
$googleheightD = '326';		// Height of your Google player

// iFilm options
$ifilmwidthD ='448';			// Width of your iFilm player
$ifilmheightD = '365';		// Height of your iFilm player

// DailyMotion options
$dailymotionwidthD ='425';	// Width of your DailyMotion player
$dailymotionheightD = '335';	// Height of your DailyMotion player

// Metacafe options
$metacafewidthD ='400';		// Width of your Metacafe player
$metacafeheightD = '345';	// Height of your Metacafe player

// Myspace options
$myspacewidthD ='425';		// Width of your Myspace player
$myspaceheightD = '360';		// Height of your Myspace player

// Break options
$breakwidthD ='464';		// Width of your Break player
$breakheightD = '392';		// Height of your Break player

// Revver options
$revverwidthD = '480';
$revverheightD = '376';
$revverIDD = '44205';

/**********************************************************************
*   End of configurable plugin options
**********************************************************************/
// General site options
$anarchy_url = get_settings('siteurl').'/wp-content/plugins/anarchy_media';
if(get_option('anarchy_accepted_domains1')) : $accepted_domains1 = get_option('anarchy_accepted_domains1');
else :
$accepted_domains1 = '';
endif;
if(get_option('anarchy_accepted_domains2')) : $accepted_domains2 = get_option('anarchy_accepted_domains2');
else :
$accepted_domains2 = '';
endif;
if(get_option('anarchy_accepted_domains3')) : $accepted_domains3 = get_option('anarchy_accepted_domains3');
else :
$accepted_domains3 = '';
endif;
if($accepted_domains2 !== '') $domain2 = ',"'. $accepted_domains2 .'"';
if($accepted_domains3 !== '') $domain3 = ',"'. $accepted_domains3 .'"';
$accepted_domains = '("'. $accepted_domains1.'"'. $domain2 . $domain3 .')';

if(get_option('anarchy_viddownloadLink')) : $viddownloadLink = get_option('anarchy_viddownloadLink');
else : $viddownloadLink = 'none';
endif;

if(get_option('anarchy_autohyperlinks')) : $autohyperlinks = get_option('anarchy_autohyperlinks');
else : $autohyperlinks = 'false';
endif;

// RTE buttons
if(get_option('anarchy_flashbutton') && (get_option('anarchy_flashbutton') == 'false')) : $flashbutton = 'false';
else : $flashbutton = 'true';
endif;

if(get_option('anarchy_directorbutton')) : $directorbutton = get_option('anarchy_directorbutton');
else : $directorbutton = 'false';
endif;

if(get_option('anarchy_videobutton') && (get_option('anarchy_videobutton') == 'false')) : $videobutton = 'false';
else : $videobutton = 'true';
endif;

// MP3 Flash player options
if(get_option('anarchy_playerloop')) : $playerloop = get_option('anarchy_playerloop');
else : $playerloop = 'no';
endif;

if(get_option('anarchy_mp3downloadLink')) : $mp3downloadLink = get_option('anarchy_mp3downloadLink');
else : $mp3downloadLink = 'none';
endif;

if(get_option('anarchy_mp3imgmargin')) : $mp3imgmargin = get_option('anarchy_mp3imgmargin');
else : $mp3imgmargin = $mp3imgmarginD; 
endif;

if(get_option('anarchy_mp3playerstyle')) : $mp3playerstyle = get_option('anarchy_mp3playerstyle');
else : $mp3playerstyle = $mp3playerstyleD; 
endif;

// Hex colours for the MP3 Flash Player (minus the #)
if(get_option('anarchy_playerbg')) : $playerbg = get_option('anarchy_playerbg');
else : $playerbg = $playerbgD; 
endif;

if(get_option('anarchy_playerleftbg')) : $playerleftbg = get_option('anarchy_playerleftbg');
else : $playerleftbg = $playerleftbgD; 
endif;

if(get_option('anarchy_playerrightbg')) : $playerrightbg = get_option('anarchy_playerrightbg');
else : $playerrightbg = $playerrightbgD; 
endif;

if(get_option('anarchy_playerrightbghover')) : $playerrightbghover = get_option('anarchy_playerrightbghover');
else : $playerrightbghover = $playerrightbghoverD; 
endif;

if(get_option('anarchy_playerlefticon')) : $playerlefticon = get_option('anarchy_playerlefticon');
else : $playerlefticon = $playerlefticonD; 
endif;

if(get_option('anarchy_playerrighticon')) : $playerrighticon = get_option('anarchy_playerrighticon');
else : $playerrighticon = $playerrighticonD; 
endif;

if(get_option('anarchy_playerrighticonhover')) : $playerrighticonhover = get_option('anarchy_playerrighticonhover');
else : $playerrighticonhover = $playerrighticonhoverD; 
endif;

if(get_option('anarchy_playertext')) : $playertext = get_option('anarchy_playertext');
else : $playertext = $playertextD; 
endif;

if(get_option('anarchy_playerslider')) : $playerslider = get_option('anarchy_playerslider');
else : $playerslider = $playersliderD; 
endif;

if(get_option('anarchy_playertrack')) : $playertrack = get_option('anarchy_playertrack');
else : $playertrack = $playertrackD; 
endif;

if(get_option('anarchy_playerloader')) : $playerloader = get_option('anarchy_playerloader');
else : $playerloader = $playerloaderD; 
endif;

if(get_option('anarchy_playerborder')) : $playerborder = get_option('anarchy_playerborder');
else : $playerborder = $playerborderD; 
endif;

// Flash video player options
if(get_option('anarchy_flvwidth')) : $flvwidth = get_option('anarchy_flvwidth');
else : $flvwidth = $flvwidthD; 
endif;

if(get_option('anarchy_flvheight')) : $flvheight = get_option('anarchy_flvheight');
else : $flvheight = $flvheightD;
endif;

if(get_option('anarchy_flvfullscreen')) : $flvfullscreen = get_option('anarchy_flvfullscreen');
else : $flvfullscreen = 'true';
endif;

// Quicktime player options
if(get_option('anarchy_qtwidth')) : $qtwidth = get_option('anarchy_qtwidth');
else : $qtwidth = $qtwidthD; 
endif;
if(get_option('anarchy_qtheight')) : $qtheight = get_option('anarchy_qtheight');
else : $qtheight = $qtheightD;
endif;
if(get_option('anarchy_qtversion')) : $qtversion = get_option('anarchy_qtversion');
else : $qtversion = $qtversionD;
endif;

if(get_option('anarchy_qtloop')) : $qtloop = get_option('anarchy_qtloop');
else : $qtloop = 'false';
endif;

if(get_option('anarchy_qtkiosk')) : $qtkiosk = get_option('anarchy_qtkiosk');
else : $qtkiosk = 'true';
endif;

if(get_option('anarchy_vidimgmargin')) : $vidimgmargin = get_option('anarchy_vidimgmargin');
else : $vidimgmargin = $vidimgmarginD; 
endif;

// WMV player options
if(get_option('anarchy_wmvwidth')) : $wmvwidth = get_option('anarchy_wmvwidth');
else : $wmvwidth = $wmvwidthD; 
endif;
if(get_option('anarchy_wmvheight')) : $wmvheight = get_option('anarchy_wmvheight');
else : $wmvheight = $wmvheightD;
endif;

// YouTube player options
if(get_option('anarchy_youtubewidth')) : $youtubewidth = get_option('anarchy_youtubewidth');
else : $youtubewidth = $youtubewidthD; 
endif;

if(get_option('anarchy_youtubeheight')) : $youtubeheight = get_option('anarchy_youtubeheight');
else : $youtubeheight = $youtubeheightD;
endif;

// Google Video options
if(get_option('anarchy_googlewidth')) : $googlewidth = get_option('anarchy_googlewidth');
else : $googlewidth = $googlewidthD; 
endif;

if(get_option('anarchy_googleheight')) : $googleheight = get_option('anarchy_googleheight');
else : $googleheight = $googleheightD;
endif;

// iFilm options
if(get_option('anarchy_ifilmwidth')) : $ifilmwidth = get_option('anarchy_ifilmwidth');
else : $ifilmwidth = $ifilmwidthD; 
endif;

if(get_option('anarchy_ifilmheight')) : $ifilmheight = get_option('anarchy_ifilmheight');
else : $ifilmheight = $ifilmheightD;
endif;

// DailyMotion options
if(get_option('anarchy_dailymotionwidth')) : $dailymotionwidth = get_option('anarchy_dailymotionwidth');
else : $dailymotionwidth = $dailymotionwidthD; 
endif;

if(get_option('anarchy_dailymotionheight')) : $dailymotionheight = get_option('anarchy_dailymotionheight');
else : $dailymotionheight = $dailymotionheightD;
endif;

// Metacafe options
if(get_option('anarchy_metacafewidth')) : $metacafewidth = get_option('anarchy_metacafewidth');
else : $metacafewidth = $metacafewidthD; 
endif;

if(get_option('anarchy_metacafeheight')) : $metacafeheight = get_option('anarchy_metacafeheight');
else : $metacafeheight = $metacafeheightD;
endif;

// Myspace options
if(get_option('anarchy_myspacewidth')) : $myspacewidth = get_option('anarchy_myspacewidth');
else : $myspacewidth = $myspacewidthD; 
endif;

if(get_option('anarchy_myspaceheight')) : $myspaceheight = get_option('anarchy_myspaceheight');
else : $myspaceheight = $myspaceheightD;
endif;

// Break options
if(get_option('anarchy_breakwidth')) : $breakwidth = get_option('anarchy_breakwidth');
else : $breakwidth = $breakwidthD; 
endif;

if(get_option('anarchy_breakheight')) : $breakheight = get_option('anarchy_breakheight');
else : $breakheight = $breakheightD;
endif;

// Revver options
if(get_option('anarchy_revverwidth')) : $revverwidth = get_option('anarchy_revverwidth');
else : $revverwidth = $revverwidthD; 
endif;

if(get_option('anarchy_revverheight')) : $revverheight = get_option('anarchy_revverheight');
else : $revverheight = $revverheightD;
endif;

if(get_option('anarchy_revverID')) : $revverID = get_option('anarchy_revverID');
else : $revverID = $revverIDD; 
endif;


/*************************************************************************
* End Configure Anarchy options
**************************************************************************/
}
?>
