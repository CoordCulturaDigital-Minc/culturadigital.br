<?php
/*
Plugin Name: Anarchy Media Player
Plugin URI: http://an-archos.com/anarchy-media-player
Description: AMP plays mp3, flv, mov, mp4, m4v, m4a, m4b, 3gp, avi, asf and wmv hypertext links directly on your webpage. Adds buttons to the post editor for embedding swf movies including Google Video etc. Edit all player options in <a href="options-general.php?page=anarchy_media/anarchy-options.php">Settings > AMP</a>
Author: An-archos
Author URI: http://an-archos.com/
Version: 2.5.1
//
For non-WordPress pages call the anarchy.js script in the html head:
<script type="text/javascript" src="http://PATH TO/wp-content/plugins/anarchy_media/anarchy_media_player.php?anarchy.js"></script>. 
//
Acknowledgments: Anarchy.js is based on various hacks of excellent scripts - Del.icio.us mp3 Playtagger javascript (http://del.icio.us/help/playtagger) as used in Taragana's Del.icio.us mp3 Player Plugin (http://blog.taragana.com/index.php/archive/taraganas-delicious-mp3-player-wordpress-plugin/) - Jeroen Wijering's Flv Player (http://www.jeroenwijering.com/?item=Flash_Video_Player) with Tradebit modifications (http://www.tradebit.com) - EMFF inspired WP Audio Player mp3 player (http://www.1pixelout.net/code/audio-player-wordpress-plugin). Flash embeds - Michael Bester's Kimili Flash Embed (http://www.kimili.com/plugins/kml_flashembed) utilising Geoff Stearns' excellent standards compliant Flash detection and embedding JavaScript (http://blog.deconcept.com/swfobject/).
//
Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
*/

require_once str_replace($_SERVER['SCRIPT_NAME'], '',
                         $_SERVER['SCRIPT_FILENAME']) . '/wp-config.php';

include_once('config.php');
include_once('autohyperlink-urls.php');
/**********************************************************************
* Append Flash, Director and Video buttons to editor
**********************************************************************/
function anarchy_mce_plugins($plugins) {    
	global $anarchy_url,  $wp_db_version;
	if ( $wp_db_version >= 6846 ) {
		$plugins['anarchymedia'] = $anarchy_url.'/tmce/anarchymedia/editor_plugin.js';
	}
	else {
		array_push($plugins, "-anarchyswf","-anarchydcr","-anarchymedia","bold");    
	}
		return $plugins;
}
function anarchy_mce_buttons($buttons) {
	global  $wp_db_version, $flashbutton, $directorbutton, $videobutton;
	if ( $wp_db_version >= 6846 ) {
		if ($flashbutton == 'true') {
			array_push($buttons, "anarchyswfbt");
			}
		if ($directorbutton == 'true') {
			array_push($buttons, "anarchydcrbt");
			}
		if ($videobutton == 'true') {
			array_push($buttons, "anarchymediabt");
			}
	}
	else {
		array_push($buttons, "separator", "anarchyswf", "anarchydcr","anarchyvideo");
	}
	return $buttons;
}
function anarchy_external_plugins() {	
	global $anarchy_url, $flashbutton, $directorbutton, $videobutton;
	if ($flashbutton == 'true') {
	echo 'tinyMCE.loadPlugin("anarchyswf", "'.$anarchy_url.'/tmce/anarchyswf/");' . "\n"; 
	}
	if ($directorbutton == 'true') {
	echo 'tinyMCE.loadPlugin("anarchydcr", "'.$anarchy_url.'/tmce/anarchydcr/");' . "\n"; 
	}
	if ($videobutton == 'true') {
	echo 'tinyMCE.loadPlugin("anarchyvideo", "'.$anarchy_url.'/tmce/anarchyvideo/");' . "\n"; 
	}

	return;
}

// Add quicktags for html source editor
function anarchy_add_quicktags() {
global  $flashbutton, $directorbutton, $videobutton;
echo <<<EOT
<script type="text/javascript">
<!--
	if(anarchyToolbar = document.getElementById("ed_toolbar")){
		var anarchyNr, anarchyBut, anarchyStart, anarchyEnd;							
EOT;
if ($flashbutton == 'true') {
echo <<<EOT
		anarchyStart = '';
		anarchyEnd = '';
		anarchyNr = edButtons.length;
		edButtons[anarchyNr] = new edButton('ed_anarchy0','SWF','', '','','-1');
		var anarchyBut = anarchyToolbar.lastChild;
		while (anarchyBut.nodeType != 1){
			anarchyBut = anarchyBut.previousSibling;
		}
		anarchyBut = anarchyBut.cloneNode(true);
		anarchyToolbar.appendChild(anarchyBut);
		anarchyBut.value = 'SWF';
		anarchyBut.title = 'Embed Flash SWF files';
		anarchyBut.onclick = function() {edInsertMedia('Flash SWF', 'swf', 'swf');}
		anarchyBut.id = "ed_anarchy0";							
EOT;
}
if ($directorbutton == 'true') {
echo <<<EOT
		anarchyStart = '';
		anarchyEnd = '';
		anarchyNr = edButtons.length;
		edButtons[anarchyNr] = new edButton('ed_anarchy1','DCR','', '','','-1');
		var anarchyBut = anarchyToolbar.lastChild;
		while (anarchyBut.nodeType != 1){
			anarchyBut = anarchyBut.previousSibling;
		}
		anarchyBut = anarchyBut.cloneNode(true);
		anarchyToolbar.appendChild(anarchyBut);
		anarchyBut.value = 'DCR';
		anarchyBut.title = 'Embed Director DCR files';
		anarchyBut.onclick = function() {edInsertMedia('Director', 'dcr', 'dcr');}
		anarchyBut.id = "ed_anarchy1";
EOT;
}
if ($videobutton == 'true') {
echo <<<EOT
		anarchyStart = '';
		anarchyEnd = '';
		anarchyNr = edButtons.length;
		edButtons[anarchyNr] = new edButton('ed_anarchy2','Media','', '','','-1');
		var anarchyBut = anarchyToolbar.lastChild;
		while (anarchyBut.nodeType != 1){
			anarchyBut = anarchyBut.previousSibling;
		}
		anarchyBut = anarchyBut.cloneNode(true);
		anarchyToolbar.appendChild(anarchyBut);
		anarchyBut.value = 'Media';
		anarchyBut.title = 'Embed media links, Youtube, Google, iFilm etc.';
		anarchyBut.onclick = function() {edInsertVideoSite();}
		anarchyBut.id = "ed_anarchy2";
		anarchyStart = '';
		anarchyEnd = '';
EOT;
}
echo <<<EOT
	}
//-->
</script>
EOT;
}
// Load the javascript for the editor buttons
function anarchy_videoquicktags_javascript() {
	global $anarchy_url;
	echo '<script type="text/javascript" src="' . $anarchy_url . '/anarchy_media_player.php?anarchy_videoquicktags.js"></script>' . "\n";
}
// Add admin options page
function anarchy_add_options_page() {
	if (function_exists('add_options_page')) {
		add_options_page(__('Anarchy Options', 'anarchy_media'), __('AMP', 'anarchy_media'), 'manage_options', 'anarchy_media/anarchy-options.php') ;
	}	
	}

/*****************************************************************
* Start Vipers Quicktags - used for director dcr and legacy
*****************************************************************/
function vipers_videoquicktags_replacer($content) {

	global $youtubewidth, $youtubeheight, $googlewidth, $googleheight, $ifilmwidth, $ifilmheight;

	$standard_youtube = '<code>[kml_flashembed movie="http://www.youtube.com/v/$1" width="'.$youtubewidth.'" height="'.$youtubeheight.'" wmode="transparent" /]</code>';

	$standard_gvideo = '<code>[kml_flashembed movie="http://video.google.com/googleplayer.swf?docId=$1" width="'.$googlewidth.'" height="'.$googleheight.'"/]</code><br /><div class="gvideo" style="font-size:10px; text-decoration: none; margin:0 0 10px 0;"><a href="javascript:void(window.open(\'http://video.google.com/googleplayer.swf?docid=$1\',\'GooglePlayer\',\'location=no,menubar=no,scrollbars=auto,status=no,toolbar=no,fullscreen=yes,dependent=no,left=1,top=1\'))">View Google Full Screen</a></div>';

	$searchfor = array(
		'/\[youtube](.*?)\[\/youtube]/i',
		'/\[youtube width="(.*?)" height="(.*?)"](.*?)\[\/youtube]/i',

		'/\[googlevideo](.*?)\[\/googlevideo]/i',
		'/\[googlevideo width="(.*?)" height="(.*?)"](.*?)\[\/googlevideo]/i',

		'/\[ifilm](.*?)\[\/ifilm]/i',
		'/\[ifilm width="(.*?)" height="(.*?)"](.*?)\[\/ifilm]/i',

		'/\[swf width="(.*?)" height="(.*?)"](.*?)\[\/swf]/i',

		'/\[dcr width="(.*?)" height="(.*?)"](.*?)\[\/dcr]/i',
	);

	$replacewith = array(
		// YouTube
		$standard_youtube,
		'<code>[kml_flashembed movie="http://www.youtube.com/v/$3" width="$1" height="$2" wmode="transparent" /]</code>',

		// Google Video
		$standard_gvideo,
		'<code>[kml_flashembed movie="http://video.google.com/googleplayer.swf?docId=$3" width="$1" height="$2"/]</code><br /><div class="gvideo" style="font-size:10px; text-decoration: none; margin:0 0 10px 0;"><a href="javascript:void(window.open(\'http://video.google.com/googleplayer.swf?docid=$1\',\'GooglePlayer\',\'location=no,menubar=no,scrollbars=auto,status=no,toolbar=no,fullscreen=yes,dependent=no,left=1,top=1\'))">View Google Full Screen</a></div>',

		// IFILM
		'<code>[kml_flashembed movie="http://www.ifilm.com/efp"  width="'.$ifilmwidth.'" height="'.$ifilmheight.'" fvars="flvbaseclip=$1"/]</code>',
		'<code>[kml_flashembed movie="http://www.ifilm.com/efp"  width="$1" height="$2" fvars="flvbaseclip=$3"/]</code>',

		// Flash swf file
		'<code>[kml_flashembed movie="$3" width="$1" height="$2" /]</code>',

		// DCR files
		'<code><OBJECT CLASSID="clsid:166B1BCA-3F9C-11CF-8075-444553540000" CODEBASE="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=8,5,1,0" WIDTH="$1" HEIGHT="$2"><PARAM NAME="SRC" VALUE="$3"><EMBED SRC="$3" WIDTH="$1" HEIGHT="$2" TYPE="application/x-director" PLUGINSPAGE="http://www.adobe.com/shockwave/download/"></EMBED></OBJECT></code>',

	);

	return preg_replace($searchfor, $replacewith, $content);
}

/*******************************************************************
*   Start Anarchy.js
********************************************************************/

if( stristr($_SERVER['REQUEST_URI'], 'anarchy.js') ) {
	header('Content-type: text/javascript');
	?>
/*
Anarchy Media Player 2.5.1
http://an-archos.com/anarchy-media-player
Makes any mp3, Flash flv, Quicktime mov, mp4, m4v, m4a, m4b and 3gp as well as wmv, avi and asf links playable directly on your webpage while optionally hiding the download link. Flash movies, including YouTube etc, use SWFObject javascript embeds - usage examples at http://blog.deconcept.com/swfobject/#examples
Anarchy.js is based on various hacks of excellent scripts - Del.icio.us mp3 Playtagger javascript (http://del.icio.us/help/playtagger) as used in Taragana's Del.icio.us mp3 Player Plugin (http://blog.taragana.com/index.php/archive/taraganas-delicious-mp3-player-wordpress-plugin/) - Jeroen Wijering's Flv Player (http://www.jeroenwijering.com/?item=Flash_Video_Player) with Tradebit modifications (http://www.tradebit.com) - EMFF inspired WP Audio Player mp3 player (http://www.1pixelout.net/code/audio-player-wordpress-plugin). Flash embeds via Geoff Stearns' excellent standards compliant Flash detection and embedding JavaScript (http://blog.deconcept.com/swfobject/).
Distributed under GNU General Public License.

For non-WP pages call script in <HEAD>:
<script type="text/javascript" src="http://PATH TO PLAYER DIRECTORY/anarchy_media/anarchy.js"></script>
*/
// Configure plugin options below

var anarchy_url = '<?php echo $anarchy_url; ?>' // http address for the anarchy-media plugin folder (no trailing slash).
var accepted_domains=new Array<?php echo $accepted_domains; ?> 	// OPTIONAL - Restrict script use to your domains. Add root domain name (minus 'http' or 'www') in quotes, add extra domains in quotes and separated by comma.
var viddownloadLink = '<?php echo $viddownloadLink; ?>'	// Download link for flv and wmv links: One of 'none' (to turn downloading off) or 'inline' to display the link. ***Use $qtkiosk for qt***.

// MP3 Flash player options
var playerloop = '<?php echo $playerloop; ?>'		// Loop the music ... yes or no?
var mp3downloadLink = '<?php echo $mp3downloadLink; ?>'	// Download for mp3 links: One of 'none' (to turn downloading off) or 'inline' to display the link.

// Hex colours for the MP3 Flash Player (minus the #)
var playerbg ='<?php echo $playerbg; ?>'				// Background colour
var playerleftbg = '<?php echo $playerleftbg; ?>'		// Left background colour
var playerrightbg = '<?php echo $playerrightbg; ?>'		// Right background colour
var playerrightbghover = '<?php echo $playerrightbghover; ?>'	// Right background colour (hover)
var playerlefticon = '<?php echo $playerlefticon; ?>'		// Left icon colour
var playerrighticon = '<?php echo $playerrighticon; ?>'		// Right icon colour
var playerrighticonhover = '<?php echo $playerrighticonhover; ?>'	// Right icon colour (hover)
var playertext = '<?php echo $playertext; ?>'			// Text colour
var playerslider = '<?php echo $playerslider; ?>'		// Slider colour
var playertrack = '<?php echo $playertrack; ?>'			// Loader bar colour
var playerloader = '<?php echo $playerloader; ?>'		// Progress track colour
var playerborder = '<?php echo $playerborder; ?>'		// Progress track border colour

// Flash video player options
var flvwidth = '<?php echo $flvwidth; ?>' 	// Width of the flv player
var flvheight = '<?php echo $flvheight; ?>'	// Height of the flv player (allow 20px for controller)
var flvfullscreen = '<?php echo $flvfullscreen; ?>' // Show fullscreen button, true or false (no auto return on Safari, double click in IE6)

//Quicktime player options
var qtloop = '<?php echo $qtloop; ?>'		// Loop Quicktime movies: true or false.
var qtwidth = '<?php echo $qtwidth; ?>'		// Width of your Quicktime player
var qtheight = '<?php echo $qtheight; ?>'	// Height of your Quicktime player (allow 16px for controller)
var qtkiosk = '<?php echo $qtkiosk; ?>'		// Allow downloads, false = yes, true = no.
// Required Quicktime version ='<?php echo $qtversion; ?>' - To set the minimum version go to Quicktime player section below and edit (quicktimeVersion >= <?php echo $qtversion; ?>) lines 228 and 266.

//WMV player options
var wmvwidth = '<?php echo $wmvwidth; ?>'	// Width of your WMV player
var wmvheight = '<?php echo $wmvheight; ?>'	// Height of your WMV player (allow 45px for WMV controller or 16px if QT player - ignored by WinIE)

// CSS styles
var mp3playerstyle = '<?php echo $mp3playerstyle; ?>'	// Flash mp3 player css style
var mp3imgmargin = '<?php echo $mp3imgmargin; ?>'		// Mp3 button image css margins
var vidimgmargin = '<?php echo $vidimgmargin; ?>'		// Video image placeholder css margins

/* ------------------ End configuration options --------------------- */

/* --------------------- Domain Check ----------------------- */
//Lite protection only, you can also use .htaccss if you're paranoid - see http://evolt.org/node/60180
var domaincheck=document.location.href //retrieve the current URL of user browser
var accepted_ok=false //set acess to false by default

if (domaincheck.indexOf("http")!=-1){ //if this is a http request
for (r=0;r<accepted_domains.length;r++){
if (domaincheck.indexOf(accepted_domains[r])!=-1){ //if a match is found
accepted_ok=true //set access to true, and break out of loop
break
}
}
}
else
accepted_ok=true

if (!accepted_ok){
alert("You\'re not allowed to directly link to this .js file on our server!")
history.back(-1)
}

/* --------------------- Flash MP3 audio player ----------------------- */
if(typeof(Anarchy) == 'undefined') Anarchy = {}
Anarchy.Mp3 = {
	playimg: null,
	player: null,
	go: function() {
		var all = document.getElementsByTagName('a')
		for (var i = 0, o; o = all[i]; i++) {
			if(o.href.match(/\.mp3$/i) && o.className!="amplink") {
				o.style.display = mp3downloadLink
				var img = document.createElement('img')
				img.src = anarchy_url+'/images/audio_mp3_play.gif'; img.title = 'Click to listen'
				img.style.margin = mp3imgmargin
				img.style.border = 'none'
				img.style.cursor = 'pointer'
				img.onclick = Anarchy.Mp3.makeToggle(img, o.href)
				o.parentNode.insertBefore(img, o)
	}}},
	toggle: function(img, url) {
		if (Anarchy.Mp3.playimg == img) Anarchy.Mp3.destroy()
		else {
			if (Anarchy.Mp3.playimg) Anarchy.Mp3.destroy()
			img.src = anarchy_url+'/images/audio_mp3_stop.gif'; Anarchy.Mp3.playimg = img;
			Anarchy.Mp3.player = document.createElement('span')
			Anarchy.Mp3.player.innerHTML = '<br /><object style="'+mp3playerstyle+'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"' +
			'codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"' +
			'width="290" height="24" id="player" align="middle">' +
			'<param name="wmode" value="transparent" />' +
			'<param name="allowScriptAccess" value="sameDomain" />' +
			'<param name="flashVars" value="bg=0x'+playerbg+'&amp;leftbg=0x'+playerleftbg+'&amp;rightbg=0x'+playerrightbg+'&amp;rightbghover=0x'+playerrightbghover+'&amp;lefticon=0x'+playerlefticon+'&amp;righticon=0x'+playerrighticon+'&amp;righticonhover=0x'+playerrighticonhover+'&amp;text=0x'+playertext+'&amp;slider=0x'+playerslider+'&amp;track=0x'+playertrack+'&amp;loader=0x'+playerloader+'&amp;border=0x'+playerborder+'&amp;autostart=yes&amp;loop='+playerloop+'&amp;soundFile='+url+'" />' +
			'<param name="movie" value="'+anarchy_url+'/player.swf" /><param name="quality" value="high" />' +
			'<embed style="'+mp3playerstyle+'" src="'+anarchy_url+'/player.swf" flashVars="bg=0x'+playerbg+'&amp;leftbg=0x'+playerleftbg+'&amp;rightbg=0x'+playerrightbg+'&amp;rightbghover=0x'+playerrightbghover+'&amp;lefticon=0x'+playerlefticon+'&amp;righticon=0x'+playerrighticon+'&amp;righticonhover=0x'+playerrighticonhover+'&amp;text=0x'+playertext+'&amp;slider=0x'+playerslider+'&amp;track=0x'+playertrack+'&amp;loader=0x'+playerloader+'&amp;border=0x'+playerborder+'&amp;autostart=yes&amp;loop='+playerloop+'&amp;soundFile='+url+'" '+
			'quality="high" wmode="transparent" width="290" height="24" name="player"' +
			'align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"' +
			' pluginspage="http://www.macromedia.com/go/getflashplayer" /></object><br />'
			img.parentNode.insertBefore(Anarchy.Mp3.player, img.nextSibling)
	}},
	destroy: function() {
		Anarchy.Mp3.playimg.src = anarchy_url+'/images/audio_mp3_play.gif'; Anarchy.Mp3.playimg = null
		Anarchy.Mp3.player.removeChild(Anarchy.Mp3.player.firstChild); Anarchy.Mp3.player.parentNode.removeChild(Anarchy.Mp3.player); Anarchy.Mp3.player = null
	},
	makeToggle: function(img, url) { return function(){ Anarchy.Mp3.toggle(img, url) }}
}

/* ----------------- Flash flv video player ----------------------- */

if(typeof(Anarchy) == 'undefined') Anarchy = {}
Anarchy.FLV = {
	go: function() {
		var all = document.getElementsByTagName('a')
		for (var i = 0, o; o = all[i]; i++) {
			if(o.href.match(/\.flv$/i) && o.className!="amplink") {
			o.style.display = viddownloadLink
			url = o.href
			var flvplayer = document.createElement('span')
			flvplayer.innerHTML = '<object type="application/x-shockwave-flash" wmode="transparent" data="'+anarchy_url+'/flvplayer.swf?click='+anarchy_url+'/images/flvplaybutton.jpg&file='+url+'&showfsbutton='+flvfullscreen+'" height="'+flvheight+'" width="'+flvwidth+'">' +
			'<param name="movie" value="'+anarchy_url+'/flvplayer.swf?click='+anarchy_url+'/images/flvplaybutton.jpg&file='+url+'&showfsbutton='+flvfullscreen+'"> <param name="wmode" value="transparent">' +
			'<embed src="'+anarchy_url+'/flvplayer.swf?file='+url+'&click='+anarchy_url+'/images/flvplaybutton.jpg&&showfsbutton='+flvfullscreen+'" ' +
			'width="'+flvwidth+'" height="'+flvheight+'" name="flvplayer" align="middle" ' +
			'play="true" loop="false" quality="high" allowScriptAccess="sameDomain" ' +
			'type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">' +
			'</embed></object><br />'
			o.parentNode.insertBefore(flvplayer, o)
	}}}}

/* ----------------------- QUICKTIME DETECT ---------------------------
// Bits of code by Chris Nott (chris[at]dithered[dot]com) and
// Geoff Stearns (geoff@deconcept.com, http://www.deconcept.com/)
--------------------------------------------------------------------- */

function getQuicktimeVersion() {
var n=navigator;
var nua=n.userAgent;
var saf=(nua.indexOf('Safari')!=-1);
var quicktimeVersion = 0;

if (saf) {
quicktimeVersion='9.0';
}
else {
var agent = navigator.userAgent.toLowerCase();

	// NS3+, Opera3+, IE5+ Mac (support plugin array):  check for Quicktime plugin in plugin array
	if (navigator.plugins != null && navigator.plugins.length > 0) {
      for (i=0; i < navigator.plugins.length; i++ ) {
         var plugin =navigator.plugins[i];
         if (plugin.name.indexOf("QuickTime") > -1) {
            quicktimeVersion = parseFloat(plugin.name.substring(18));
         }
      }
	}
   	else if (window.ActiveXObject) {
		execScript('on error resume next: qtObj = IsObject(CreateObject("QuickTime.QuickTime.4"))','VBScript');
			if (qtObj == true) {
				quicktimeVersion = 100;
				}
			else {
				quicktimeVersion = 0;
			}
		}
	}
	return quicktimeVersion;
}

/* ----------------------- Quicktime player ------------------------ */

if(typeof(Anarchy) == 'undefined') Anarchy = {}
Anarchy.MOV = {
	playimg: null,
	player: null,
	go: function() {
		var all = document.getElementsByTagName('a')
		Anarchy.MOV.preview_images = { }
		for (var i = 0, o; o = all[i]; i++) {
			if(o.href.match(/\.mov$|\.mp4$|\.m4v$|\.m4b$|\.3gp$/i) && o.className!="amplink") {
				o.style.display = 'none'
				var img = document.createElement('img')
				Anarchy.MOV.preview_images[i] = document.createElement('img') ;
				Anarchy.MOV.preview_images[i].src = o.href + '.jpg' ;
				Anarchy.MOV.preview_images[i].defaultImg = img ;
				Anarchy.MOV.preview_images[i].replaceDefault = function() {
				  this.defaultImg.src = this.src ;
				}
				Anarchy.MOV.preview_images[i].onload = Anarchy.MOV.preview_images[i].replaceDefault ;
				img.src = anarchy_url+'/images/vid_play.gif'
				img.title = 'Click to play video'
				img.style.margin = vidimgmargin
				img.style.padding = '0px'
				img.style.display = 'block'
				img.style.border = 'none'
				img.style.cursor = 'pointer'
				img.height = qtheight
				img.width = qtwidth
				img.onclick = Anarchy.MOV.makeToggle(img, o.href)
				o.parentNode.insertBefore(img, o)
	}}},
	toggle: function(img, url) {
		if (Anarchy.MOV.playimg == img) Anarchy.MOV.destroy()
		else {
			if (Anarchy.MOV.playimg) Anarchy.MOV.destroy()
			img.src = anarchy_url+'/images/vid_play.gif'
			img.style.display = 'none';
			Anarchy.MOV.playimg = img;
			Anarchy.MOV.player = document.createElement('p')
			var quicktimeVersion = getQuicktimeVersion()
			if (quicktimeVersion >= <?php echo $qtversion; ?>) {
			Anarchy.MOV.player.innerHTML = '<embed src="'+url+'" width="'+qtwidth+'" height="'+qtheight+'" loop="'+qtloop+'" autoplay="true" controller="true" border="0" type="video/quicktime" kioskmode="'+qtkiosk+'" scale="tofit"></embed><br />'
          img.parentNode.insertBefore(Anarchy.MOV.player, img.nextSibling)
          }
		else
			Anarchy.MOV.player.innerHTML = '<a href="http://www.apple.com/quicktime/download/" target="_blank"><img src="'+anarchy_url+'/images/getqt.jpg"></a>'
          img.parentNode.insertBefore(Anarchy.MOV.player, img.nextSibling)
	}},
	destroy: function() {
	},
	makeToggle: function(img, url) { return function(){ Anarchy.MOV.toggle(img, url) }}
}

/* --------------------- MPEG 4 Audio Quicktime player ---------------------- */

if(typeof(Anarchy) == 'undefined') Anarchy = {}
Anarchy.M4a = {
	playimg: null,
	player: null,
	go: function() {
		var all = document.getElementsByTagName('a')
		for (var i = 0, o; o = all[i]; i++) {
			if(o.href.match(/\.m4a$/i) && o.className!="amplink") {
				o.style.display = 'none'
				var img = document.createElement('img')
				img.src = anarchy_url+'/images/audio_mp4_play.gif'; img.title = 'Click to listen'
				img.style.margin = mp3imgmargin
				img.style.border = 'none'
				img.style.cursor = 'pointer'
				img.onclick = Anarchy.M4a.makeToggle(img, o.href)
				o.parentNode.insertBefore(img, o)
	}}},
	toggle: function(img, url) {
		if (Anarchy.M4a.playimg == img) Anarchy.M4a.destroy()
		else {
			if (Anarchy.M4a.playimg) Anarchy.M4a.destroy()
			img.src = anarchy_url+'/images/audio_mp4_stop.gif'; Anarchy.M4a.playimg = img;
			Anarchy.M4a.player = document.createElement('p')
			var quicktimeVersion = getQuicktimeVersion()
			if (quicktimeVersion >= <?php echo $qtversion; ?>) {
			Anarchy.M4a.player.innerHTML = '<embed src="'+url+'" width="160" height="16" loop="'+qtloop+'" autoplay="true" controller="true" border="0" type="video/quicktime" kioskmode="'+qtkiosk+'" ></embed><br />'
          img.parentNode.insertBefore(Anarchy.M4a.player, img.nextSibling)
          }
		else
			Anarchy.M4a.player.innerHTML = '<a href="http://www.apple.com/quicktime/download/" target="_blank"><img src="'+anarchy_url+'/images/getqt.jpg"></a>'
          img.parentNode.insertBefore(Anarchy.M4a.player, img.nextSibling)
	}},
	destroy: function() {
		Anarchy.M4a.playimg.src = anarchy_url+'/images/audio_mp4_play.gif'; Anarchy.M4a.playimg = null
		Anarchy.M4a.player.removeChild(Anarchy.M4a.player.firstChild); Anarchy.M4a.player.parentNode.removeChild(Anarchy.M4a.player); Anarchy.M4a.player = null
	},
	makeToggle: function(img, url) { return function(){ Anarchy.M4a.toggle(img, url) }}
}

/* ----------------------- WMV player -------------------------- */

if(typeof(Anarchy) == 'undefined') Anarchy = {}
Anarchy.WMV = {
	playimg: null,
	player: null,
	go: function() {
		var all = document.getElementsByTagName('a')
		for (var i = 0, o; o = all[i]; i++) {
			if(o.href.match(/\.asf$|\.avi$|\.wmv$/i) && o.className!="amplink") {
				o.style.display = viddownloadLink
				var img = document.createElement('img')
				img.src = anarchy_url+'/images/vid_play.gif'; img.title = 'Click to play video'
				img.style.margin = '0px'
				img.style.padding = '0px'
				img.style.display = 'block'
				img.style.border = 'none'
				img.style.cursor = 'pointer'
				img.height = qtheight
				img.width = qtwidth
				img.onclick = Anarchy.WMV.makeToggle(img, o.href)
				o.parentNode.insertBefore(img, o)
	}}},
	toggle: function(img, url) {
		if (Anarchy.WMV.playimg == img) Anarchy.WMV.destroy()
		else {
			  if (Anarchy.WMV.playimg) Anarchy.WMV.destroy()
			  img.src = anarchy_url+'/images/vid_play.gif'
			  img.style.display = 'none';
			  Anarchy.WMV.playimg = img;
			  Anarchy.WMV.player = document.createElement('span')
			  if(navigator.userAgent.indexOf('Mac') != -1) {
			  Anarchy.WMV.player.innerHTML = '<embed src="'+url+'" width="'+qtwidth+'" height="'+qtheight+'" loop="'+qtloop+'" autoplay="true" controller="true" border="0" type="video/quicktime" kioskmode="'+qtkiosk+'" scale="tofit" pluginspage="http://www.apple.com/quicktime/download/"></embed><br />'
			  img.parentNode.insertBefore(Anarchy.WMV.player, img.nextSibling)
			  } else {
			  if (navigator.plugins && navigator.plugins.length) {
			  Anarchy.WMV.player.innerHTML = '<embed type="application/x-mplayer2" src="'+url+'" ' +
			  'showcontrols="1" ShowStatusBar="1" autostart="1" displaySize="4"' +
			  'pluginspage="http://www.microsoft.com/Windows/Downloads/Contents/Products/MediaPlayer/"' +
			  'width="'+wmvwidth+'" height="'+wmvheight+'">' +
			  '</embed><br />'
			  img.parentNode.insertBefore(Anarchy.WMV.player, img.nextSibling)
			  } else {
				Anarchy.WMV.player.innerHTML = '<object classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" width="'+wmvwidth+'" height="'+wmvheight+'" id="player"> ' +
			  '<param name="url" value="'+url+'" /> ' +
			  '<param name="autoStart" value="True" /> ' +
			  '<param name="stretchToFit" value="True" /> ' +
			  '<param name="showControls" value="True" /> ' +
			  '<param name="ShowStatusBar" value="True" /> ' +
			  '<embed type="application/x-mplayer2" src="'+url+'" ' +
			  'showcontrols="1" ShowStatusBar="1" autostart="1" displaySize="4"' +
			  'pluginspage="http://www.microsoft.com/Windows/Downloads/Contents/Products/MediaPlayer/"' +
			  'width="'+wmvwidth+'" height="'+wmvheight+'">' +
			  '</embed>' +
			  '</object><br />'
			  img.parentNode.insertBefore(Anarchy.WMV.player, img.nextSibling)
			  }}
	}},
	destroy: function() {
		Anarchy.WMV.playimg.src = anarchy_url+'/images/vid_play.gif'
		Anarchy.WMV.playimg.style.display = 'inline'; Anarchy.WMV.playimg = null
		Anarchy.WMV.player.removeChild(Anarchy.WMV.player.firstChild);
		Anarchy.WMV.player.parentNode.removeChild(Anarchy.WMV.player);
		Anarchy.WMV.player = null
	},
	makeToggle: function(img, url) { return function(){ Anarchy.WMV.toggle(img, url) }}
}

/* ----------------- Trigger players onload ----------------------- */

Anarchy.addLoadEvent = function(f) { var old = window.onload
	if (typeof old != 'function') window.onload = f
	else { window.onload = function() { old(); f() }}
}

Anarchy.addLoadEvent(Anarchy.Mp3.go)
Anarchy.addLoadEvent(Anarchy.FLV.go)
Anarchy.addLoadEvent(Anarchy.MOV.go)
Anarchy.addLoadEvent(Anarchy.M4a.go)
Anarchy.addLoadEvent(Anarchy.WMV.go)

/**
 * SWFObject v1.5: Flash Player detection and embed - http://blog.deconcept.com/swfobject/
 *
 * SWFObject is (c) 2006 Geoff Stearns and is released under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 *
 */
if(typeof deconcept=="undefined"){var deconcept=new Object();}if(typeof deconcept.util=="undefined"){deconcept.util=new Object();}if(typeof deconcept.SWFObjectUtil=="undefined"){deconcept.SWFObjectUtil=new Object();}deconcept.SWFObject=function(_1,id,w,h,_5,c,_7,_8,_9,_a){if(!document.getElementById){return;}this.DETECT_KEY=_a?_a:"detectflash";this.skipDetect=deconcept.util.getRequestParameter(this.DETECT_KEY);this.params=new Object();this.variables=new Object();this.attributes=new Array();if(_1){this.setAttribute("swf",_1);}if(id){this.setAttribute("id",id);}if(w){this.setAttribute("width",w);}if(h){this.setAttribute("height",h);}if(_5){this.setAttribute("version",new deconcept.PlayerVersion(_5.toString().split(".")));}this.installedVer=deconcept.SWFObjectUtil.getPlayerVersion();if(!window.opera&&document.all&&this.installedVer.major>7){deconcept.SWFObject.doPrepUnload=true;}if(c){this.addParam("bgcolor",c);}var q=_7?_7:"high";this.addParam("quality",q);this.setAttribute("useExpressInstall",false);this.setAttribute("doExpressInstall",false);var _c=(_8)?_8:window.location;this.setAttribute("xiRedirectUrl",_c);this.setAttribute("redirectUrl","");if(_9){this.setAttribute("redirectUrl",_9);}};deconcept.SWFObject.prototype={useExpressInstall:function(_d){this.xiSWFPath=!_d?"expressinstall.swf":_d;this.setAttribute("useExpressInstall",true);},setAttribute:function(_e,_f){this.attributes[_e]=_f;},getAttribute:function(_10){return this.attributes[_10];},addParam:function(_11,_12){this.params[_11]=_12;},getParams:function(){return this.params;},addVariable:function(_13,_14){this.variables[_13]=_14;},getVariable:function(_15){return this.variables[_15];},getVariables:function(){return this.variables;},getVariablePairs:function(){var _16=new Array();var key;var _18=this.getVariables();for(key in _18){_16.push(key+"="+_18[key]);}return _16;},getSWFHTML:function(){var _19="";if(navigator.plugins&&navigator.mimeTypes&&navigator.mimeTypes.length){if(this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType","PlugIn");this.setAttribute("swf",this.xiSWFPath);}_19="<embed type=\"application/x-shockwave-flash\" src=\""+this.getAttribute("swf")+"\" width=\""+this.getAttribute("width")+"\" height=\""+this.getAttribute("height")+"\"";_19+=" id=\""+this.getAttribute("id")+"\" name=\""+this.getAttribute("id")+"\" ";var _1a=this.getParams();for(var key in _1a){_19+=[key]+"=\""+_1a[key]+"\" ";}var _1c=this.getVariablePairs().join("&");if(_1c.length>0){_19+="flashvars=\""+_1c+"\"";}_19+="/>";}else{if(this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType","ActiveX");this.setAttribute("swf",this.xiSWFPath);}_19="<object id=\""+this.getAttribute("id")+"\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\""+this.getAttribute("width")+"\" height=\""+this.getAttribute("height")+"\">";_19+="<param name=\"movie\" value=\""+this.getAttribute("swf")+"\" />";var _1d=this.getParams();for(var key in _1d){_19+="<param name=\""+key+"\" value=\""+_1d[key]+"\" />";}var _1f=this.getVariablePairs().join("&");if(_1f.length>0){_19+="<param name=\"flashvars\" value=\""+_1f+"\" />";}_19+="</object>";}return _19;},write:function(_20){if(this.getAttribute("useExpressInstall")){var _21=new deconcept.PlayerVersion([6,0,65]);if(this.installedVer.versionIsValid(_21)&&!this.installedVer.versionIsValid(this.getAttribute("version"))){this.setAttribute("doExpressInstall",true);this.addVariable("MMredirectURL",escape(this.getAttribute("xiRedirectUrl")));document.title=document.title.slice(0,47)+" - Flash Player Installation";this.addVariable("MMdoctitle",document.title);}}if(this.skipDetect||this.getAttribute("doExpressInstall")||this.installedVer.versionIsValid(this.getAttribute("version"))){var n=(typeof _20=="string")?document.getElementById(_20):_20;n.innerHTML=this.getSWFHTML();return true;}else{if(this.getAttribute("redirectUrl")!=""){document.location.replace(this.getAttribute("redirectUrl"));}}return false;}};deconcept.SWFObjectUtil.getPlayerVersion=function(){var _23=new deconcept.PlayerVersion([0,0,0]);if(navigator.plugins&&navigator.mimeTypes.length){var x=navigator.plugins["Shockwave Flash"];if(x&&x.description){_23=new deconcept.PlayerVersion(x.description.replace(/([a-zA-Z]|\s)+/,"").replace(/(\s+r|\s+b[0-9]+)/,".").split("."));}}else{if(navigator.userAgent&&navigator.userAgent.indexOf("Windows CE")>=0){var axo=1;var _26=3;while(axo){try{_26++;axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash."+_26);_23=new deconcept.PlayerVersion([_26,0,0]);}catch(e){axo=null;}}}else{try{var axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");}catch(e){try{var axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");_23=new deconcept.PlayerVersion([6,0,21]);axo.AllowScriptAccess="always";}catch(e){if(_23.major==6){return _23;}}try{axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash");}catch(e){}}if(axo!=null){_23=new deconcept.PlayerVersion(axo.GetVariable("$version").split(" ")[1].split(","));}}}return _23;};deconcept.PlayerVersion=function(_29){this.major=_29[0]!=null?parseInt(_29[0]):0;this.minor=_29[1]!=null?parseInt(_29[1]):0;this.rev=_29[2]!=null?parseInt(_29[2]):0;};deconcept.PlayerVersion.prototype.versionIsValid=function(fv){if(this.major<fv.major){return false;}if(this.major>fv.major){return true;}if(this.minor<fv.minor){return false;}if(this.minor>fv.minor){return true;}if(this.rev<fv.rev){return false;}return true;};deconcept.util={getRequestParameter:function(_2b){var q=document.location.search||document.location.hash;if(_2b==null){return q;}if(q){var _2d=q.substring(1).split("&");for(var i=0;i<_2d.length;i++){if(_2d[i].substring(0,_2d[i].indexOf("="))==_2b){return _2d[i].substring((_2d[i].indexOf("=")+1));}}}return "";}};deconcept.SWFObjectUtil.cleanupSWFs=function(){var _2f=document.getElementsByTagName("OBJECT");for(var i=_2f.length-1;i>=0;i--){_2f[i].style.display="none";for(var x in _2f[i]){if(typeof _2f[i][x]=="function"){_2f[i][x]=function(){};}}}};if(deconcept.SWFObject.doPrepUnload){deconcept.SWFObjectUtil.prepUnload=function(){__flash_unloadHandler=function(){};__flash_savedUnloadHandler=function(){};window.attachEvent("onunload",deconcept.SWFObjectUtil.cleanupSWFs);};window.attachEvent("onbeforeunload",deconcept.SWFObjectUtil.prepUnload);}if(Array.prototype.push==null){Array.prototype.push=function(_32){this[this.length]=_32;return this.length;};}if(!document.getElementById&&document.all){document.getElementById=function(id){return document.all["id"];};}var getQueryParamValue=deconcept.util.getRequestParameter;var FlashObject=deconcept.SWFObject;var SWFObject=deconcept.SWFObject;
<?php die(); }

/***********************************************************************
* Start editor buttons javascript
************************************************************************/

if( stristr($_SERVER['REQUEST_URI'], 'anarchy_videoquicktags.js') ) {
	global $anarchy_url;
	header('Content-type: text/javascript');
	?>
	
/* --------------------- Anarchy Video Quicktags --------------------- */

function edInsertVideoSite()
{
	var videoURL = prompt('Please enter the full HTTP address (url) to your: \nmp3, mov etc. media file or your GOOGLE, YOUTUBE, METACAFE, iFILM, REVVER, MYSPACE, ATOMFILMS or GoEar web page. \n\nFor DAILYMOTION and BREAK videos and iTUNES IMIXES just copy and paste the code from their embeddable players.','Enter your player url here');

	if (videoURL)
	{
		mediavid = new RegExp("^http:\/\/(.*)(\.mp3$|\.mov$|\.mp4$|\.m4v$|\.m4a$|\.m4b$|\.3gp$|\.wmv$|\.avi$|\.asf$)")
		flvvid = new RegExp("^http:\/\/(.*)(\.flv$)")
		googlevid = new RegExp("video\.google\.(.*)?\/videoplay")
		youtubevid = new RegExp("youtube\.com\/watch")
		dailymotionvid = new RegExp("dailymotion\.com\/swf")
		ifilmvid = new RegExp("spike\.com\/video\/")
		metacafevid = new RegExp("metacafe\.com\/watch\/")
		goearvid = new RegExp("goear\.com\/listen\.php")
		myspacevid = new RegExp("vids\.myspace\.com\/index\.cfm")
		revvervid = new RegExp("one\.revver\.com\/watch\/|revver\.com\/video\/|flash\.revver\.com\/player\/1\.0\/player\.swf")
		ipodplayer = new RegExp("ax\.phobos\.apple\.com\.edgesuite\.net\/flash\/feedreader\.swf")
		atomvid = new RegExp("atomfilms\.com(:80)?\/film")
		breakvid = new RegExp("embed\.break\.com\/")

		if(mediavid.test(videoURL)) {
		anarchy_settext('<a href="' + videoURL + '" title="Anarchy Media Player - Right click to download file"><em>Download</em></a> Title');		
		}
		else if(flvvid.test(videoURL)) {
		anarchy_settext('<code>[kml_flashembed movie="<?php echo $anarchy_url; ?>/flvplayer.swf" fvars="click=<?php echo $anarchy_url; ?>/images/flvplaybutton.jpg;file=' + videoURL + ';showfsbutton=true" width="400" height="320" allowfullscreen="true" /]</code>');
		}		
		else if(googlevid.test(videoURL)) {
		videoID1 = videoURL.replace(/\.(.*)\/videoplay/, '.google.com/googleplayer.swf')
		videoID = videoID1.replace(/&(.*)?/, "")
		anarchy_settext('<code>[kml_flashembed movie="' + videoID + '" width="<?php echo $googlewidth; ?>" height="<?php echo $googleheight; ?>" allowfullscreen="true" fvars="fs=true" /]</code>');		
		}
		else if(youtubevid.test(videoURL)) {
		videoID1 = videoURL.replace(/watch\?v\=/, 'v/')
		videoID = videoID1.replace(/&(.*)?/, "")
		anarchy_settext('<code>[kml_flashembed movie="' + videoID + '" width="<?php echo $youtubewidth; ?>" height="<?php echo $youtubeheight; ?>" allowfullscreen="true" fvars="fs=1" /]</code>');
		}
		else if(dailymotionvid.test(videoURL)) {
		videoID = videoURL.replace(/^(.*)src=\"(.*)\" type=\"(.*)$/i, "$2")
		anarchy_settext('<code>[kml_flashembed movie="' + videoID + '" width="<?php echo $dailymotionwidth; ?>" height="<?php echo $dailymotionheight; ?>" allowfullscreen="true" /]</code>');
		}
		else if(ifilmvid.test(videoURL)) {
		videoID1 = videoURL.replace(/http:\/\/www\.spike\.com\/video\/(.*)\/([a-z0-9])/i, "$2")
		videoID = videoID1.replace(/\?(.*)?/, "")
		anarchy_settext('<code>[kml_flashembed movie="http://www.spike.com/efp" width="<?php echo $ifilmwidth; ?>" height="<?php echo $ifilmheight; ?>" fvars="flvbaseclip=' + videoID + '" allowfullscreen="true" /]</code>');	
		}
		else if(metacafevid.test(videoURL)) {
		videoID1 = videoURL.replace(/watch/i, "fplayer")
		videoID = videoID1.replace(/\/$/, ".swf")
		anarchy_settext('<code>[kml_flashembed movie="' + videoID + '" width="<?php echo $metacafewidth; ?>" height="<?php echo $metacafeheight; ?>" allowfullscreen="true" /]</code>');
		}
		else if(goearvid.test(videoURL)) {
		videoID1 = videoURL.replace(/http:\/\/www\.goear\.com\/listen\.php\?v=([a-z0-9])/i, "$1")
		videoID = videoID1.replace(/\&(.*)?/, "")
		anarchy_settext('<code>[kml_flashembed movie="http://www.goear.com/files/localplayer.swf" width="366" height="75" allowfullscreen="true" fvars="file=' + videoID + '" /]</code>');
		}		
		else if(myspacevid.test(videoURL)) {
		videoID = videoURL.replace(/http:\/\/vids\.myspace\.com\/index\.cfm\?fuseaction=vids\.individual&videoid=([a-z0-9])/i, "$1")
		anarchy_settext('<code>[kml_flashembed movie="http://mediaservices.myspace.com/services/media/embed.aspx/" width="<?php echo $myspacewidth; ?>" height="<?php echo $myspaceheight; ?>" allowfullscreen="true" fvars="m=' + videoID + '" /]</code>');
		}
		else if(revvervid.test(videoURL)) {
		videoID = videoURL.replace(/^http:\/\/one\.revver\.com\/watch\/(.*)\/flv\/affiliate\/(.*)/i, "$1 ; affiliateId=$2")
		videoID = videoID.replace(/^http:\/\/one\.revver\.com\/watch\/(.*)\/flv$/i, "$1 ; affiliateId=<?php echo $revverID; ?>")
		videoID = videoID.replace(/^http:\/\/one\.revver\.com\/watch\/(.*)$/i, "$1 ; affiliateId=<?php echo $revverID; ?>")
		videoID = videoID.replace(/^http:\/\/flash\.revver\.com\/player\/1\.0\/player\.swf\?mediaId=(.*)&affiliateId=(.*)/i, "$1 ; affiliateId=$2")
		videoID = videoID.replace(/^(.*)flashvars=\"mediaId=(.*)&affiliateId=(.*)\" wmode(.*)$/i, "$2 ; affiliateId=$3")		
		videoID = videoURL.replace(/^http:\/\/revver\.com\/video\/(.*)\/(.*)\//i, "$1")
		anarchy_settext('<code>[kml_flashembed movie="http://flash.revver.com/player/1.0/player.swf" width="<?php echo $revverwidth; ?>" height="<?php echo $revverheight; ?>" fvars="mediaId=' + videoID + '" allowfullscreen="true" /]</code>');
		}
		else if(ipodplayer.test(videoURL)) {
		videoID = videoURL.replace(/^(.*)src=\"(.*)\" quality=\"(.*)$/i, "$2")
		anarchy_settext('<code>[kml_flashembed movie="' + videoID + '" width="300" height="330" allowfullscreen="true" /]</code>');
		}
		else if(breakvid.test(videoURL)) {
		videoID = videoURL.replace(/^(.*)src=\"(.*)\" type=\"(.*)$/i, "$2")
		anarchy_settext('<code>[kml_flashembed movie="' + videoID + '" width="<?php echo $breakwidth; ?>" height="<?php echo $breakheight; ?>" allowfullscreen="true" /]</code>');
		}
		else {
		alert("Anarchy doesn't recognise this URL")
		}
	}
}
function edInsertMedia(nicename, extension, tag)
{
	if (tag == 'swf')
	{
		var URL = prompt('Please enter the FULL URL to the ' + nicename + ' movie file:\nExample: http://www.yoursite.com/mymovie.' + extension,'Enter URL here');
		if (URL)
		{
			var width = prompt('How many pixels WIDE is this movie?');
			if (width)
			{
				var height = prompt('How many pixels TALL is this movie?');
				if (height)
				{
					anarchy_settext('<code>[kml_flashembed movie="' + URL + '" width="' + width +'" height="' + height + '" allowfullscreen="true" /]</code>');
				}
			}
		}
	}
	else {
		var URL = prompt('Please enter the FULL URL to the ' + nicename + ' movie file:\nExample: http://www.yoursite.com/mymovie.' + extension,'Enter URL here');
		if (URL)
		{
			var width = prompt('How many pixels WIDE is this movie?');
			if (width)
			{
				var height = prompt('How many pixels TALL is this movie?');
				if (height)
				{
					anarchy_settext('<code>[' + tag + ' width="' + width + '" height="' + height + '"]' + URL  + '[/' + tag + ']</code>');
				}
			}
		}
	}
}
function anarchy_settext(text) {
	if (document.getElementById("quicktags").style.display == "none") {
		window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, text);
		tinyMCE.execCommand("mceCleanup");
	} 
	else {
		edInsertContent(edCanvas, text);
	}
}

<?php die(); }

/***********************************************************************
*	KIMILI FLASH EMBED
*
*	Copyright 2008 Michael Bester (http://www.kimili.com)
*	Released under the GNU General Public License (http://www.gnu.org/licenses/gpl.html)
*
*/

/***********************************************************************
*	Global Vars
************************************************************************/

$kml_request_type		= "";
$kml_flashembed_ver		= "1.4.3";
$kml_flashembed_root	= get_settings('siteurl') . '/wp-content/plugins/'.dirname(plugin_basename(__FILE__));


/***********************************************************************
*	Run the main function 
************************************************************************/

function kml_flashembed($content) {
	$pattern = '/(<p>[\s\n\r]*)??(<code>[\s\n\r]*)??(([\[<]KML_(FLASH|SWF)EMBED.*\/[\]>])|([\[<]KML_(FLASH|SWF)EMBED.*[\]>][\[<]\/KML_(FLASH|SWF)EMBED[\]>]))([\s\n\r]*<\/code>)??([\s\n\r]*<\/p>)??/Umi'; 
	$result = preg_replace_callback($pattern,'kml_flashembed_parse_kfe_tags',$content);
	return $result;	
}


/***********************************************************************
*	Parse out the KFE Tags
************************************************************************/

function kml_flashembed_parse_kfe_tags($match) {
		
	$r	= "";
			
	# Clean up and untexturize tag
	$strip		= array('[KML_FLASHEMBED',
						'][/KML_FLASHEMBED]',
						'[kml_flashembed',
						'][/kml_flashembed]',
						'[KML_SWFEMBED',
						'][/KML_SWFEMBED]',
						'[kml_swfembed',
						'][/kml_swfembed]',
						'/]',
						'<KML_FLASHEMBED',
						'></KML_FLASHEMBED>',
						'<kml_flashembed',
						'></kml_flashembed>',
						'<KML_SWFEMBED',
						'></KML_SWFEMBED>',
						'<kml_swfembed',
						'></kml_swfembed>',
						'/>',
						'\n',
						'<br>',
						'<br />',
						'<p>',
						'</p>',
						'<code>',
						'</code>'
						);
						
	$elements	= str_replace($strip, '', $match[0]);
	
	$elements	= preg_replace("/=(\s*)\"/", "==`", $elements);
	$elements	= preg_replace("/=(\s*)&Prime;/", "==`", $elements);
	$elements	= preg_replace("/=(\s*)&prime;/", "==`", $elements);
	$elements	= preg_replace("/=(\s*)&#8221;/", "==`", $elements);
	$elements	= preg_replace("/\"(\s*)/", "`| ", $elements);
	$elements	= preg_replace("/&Prime;(\s*)/", "`|", $elements);
	$elements	= preg_replace("/&prime;(\s*)/", "`|", $elements);
	$elements	= preg_replace("/&#8221;(\s*)/", "`|", $elements);
	$elements	= preg_replace("/&#8243;(\s*)/", "`|", $elements);
	$elements	= preg_replace("/&#8216;(\s*)/", "'", $elements);
	$elements	= preg_replace("/&#8217;(\s*)/", "'", $elements);
	
	$attpairs	= preg_split('/\|/', $elements, -1, PREG_SPLIT_NO_EMPTY);
	$atts		= array();
	
	// Create an associative array of the attributes
	for ($x = 0; $x < count($attpairs); $x++) {
		
		$attpair		= explode('==', $attpairs[$x]);
		$attn			= trim(strtolower($attpair[0]));
		$attv			= preg_replace("/`/", "", trim($attpair[1]));
		$atts[$attn]	= $attv;
	}
	
	if (isset($atts['movie']) && isset($atts['height']) && isset($atts['width'])) {
		
		$atts['fversion']	= (isset($atts['fversion'])) ? $atts['fversion'] : 6;
		
		if (isset($atts['fvars'])) {
			$fvarpair_regex		= "/(?<!([$|\?]\{))\s+;\s+(?!\})/";
			$atts['fvars']		= preg_split($fvarpair_regex, $atts['fvars'], -1, PREG_SPLIT_NO_EMPTY);
		}
		
		// Convert any quasi-HTML in alttext back into tags
		$atts['alttext']		= (isset($atts['alttext'])) ? preg_replace("/{(.*?)}/i", "<$1>", $atts['alttext']) : '';
		
		// If we're not serving up a feed, generate the script tags
		if ($GLOBALS['kml_request_type'] != "feed") {
			$r	= kml_flashembed_build_fo_script($atts);
		} else {
			$r	= kml_flashembed_build_object_tag($atts);
		}
	}
 	return $r; 
}


/***********************************************************************
*	Build the Javascript from the tags
************************************************************************/

function kml_flashembed_build_fo_script($atts) {
	
	global $kml_flashembed_root;
	
	if (is_array($atts)) extract($atts);
	
	$out	= array();
	$ret	= "";
	
	$rand	= mt_rand();  // For making sure this instance is unique
	
	// Extract the filename minus the extension...
	$swfname	= (strrpos($movie, "/") === false) ?
							$movie :
							substr($movie, strrpos($movie, "/") + 1, strlen($movie));
	$swfname	= (strrpos($swfname, ".") === false) ?
							$swfname :
							substr($swfname, 0, strrpos($swfname, "."));
	
	// ... to use as a default ID if an ID is not defined.
	$fid			= (isset($fid)) ? $fid : "fm_" . $swfname . "_" . $rand;
	// ... as well as an empty target if that isn't defined.
	if (empty($target)) {              
		$targname	= "so_targ_" . $swfname . "_" . $rand;
		$classname	= (empty($targetclass)) ? "flashmovie" : $targetclass;
		// Create a target div
		$out[]		= '<div id="' . $targname . '" class="' . $classname . '">'.$alttext.'</div>';
		$target	= $targname;
	}
  	
	// Set variables for rendering JS
	$movie 				= '"'.$movie.'",'; 
	$fid 				= '"'.$fid.'",'; 
	$width 				= '"'.$width.'",'; 
	$height				= '"'.$height.'",';
	$fversion			= '"'.$fversion.'",';
	$bgcolor			= (isset($bgcolor)) ? '"'.$bgcolor.'",' : '"",';
	$useexpressinstall	= (isset($useexpressinstall) && $useexpressinstall == 'true') ? true : false;
	$quality			= (isset($quality)) ? '"'.$quality.'",' : '"",';
	$xiredirecturl		= (isset($xiredirecturl)) ? '"'.$xiredirecturl.'",' : '"",';
	$redirecturl		= (isset($redirecturl)) ? '"'.$redirecturl.'",' : '"",';
	$detectKey			= (isset($detectKey)) ? '"'.$detectKey.'"' : '""';
	$fvars				= (isset($fvars)) ? $fvars : array();				
	
									$out[] = '';
						  	  		$out[] = '<script type="text/javascript">';
						  	  		$out[] = '	// <![CDATA[';
									$out[] = '';
						  	  		$out[] = '	var so_' . $rand . ' = new SWFObject('.$movie . $fid . $width . $height . $fversion . $bgcolor . $quality . $xiredirecturl . $redirecturl . $detectKey . ');';
	if (isset($play))				$out[] = '	so_' . $rand . '.addParam("play", "' . $play . '");';
	if (isset($loop))				$out[] = '	so_' . $rand . '.addParam("loop", "' . $loop . '");';
	if (isset($menu)) 				$out[] = '	so_' . $rand . '.addParam("menu", "' . $menu . '");';
	if (isset($scale)) 				$out[] = '	so_' . $rand . '.addParam("scale", "' . $scale . '");';
	if (isset($wmode)) 				$out[] = '	so_' . $rand . '.addParam("wmode", "' . $wmode . '");';
	if (isset($align)) 				$out[] = '	so_' . $rand . '.addParam("align", "' . $align . '");';
	if (isset($salign)) 			$out[] = '	so_' . $rand . '.addParam("salign", "' . $salign . '");';    
	if (isset($base)) 	   		 	$out[] = '	so_' . $rand . '.addParam("base", "' . $base . '");';
	if (isset($allowscriptaccess))	$out[] = '	so_' . $rand . '.addParam("allowScriptAccess", "' . $allowscriptaccess . '");';
	if (isset($allowfullscreen))	$out[] = '	so_' . $rand . '.addParam("allowFullScreen", "' . $allowfullscreen . '");';
	if ($useexpressinstall) {
		$xiswf = '/wp-content/plugins/'. plugin_root() . '/expressinstall.swf';
									$out[] = '	so_' . $rand . '.useExpressInstall("' . $xiswf . '");';
	}		
	// Loop through and add any name/value pairs in the $fvars attribute
	for ($i = 0; $i < count($fvars); $i++) {
		$thispair	= trim($fvars[$i]);
		$nvpair		= explode("=",$thispair);
		$name		= trim($nvpair[0]);
		$value		= "";
		for ($j = 1; $j < count($nvpair); $j++) {			// In case someone passes in a fvars with additional "="       
			$value		.= trim($nvpair[$j]);
			$value		= preg_replace('/&#038;/', '&', $value);
			if ((count($nvpair) - 1)  != $j) {
				$value	.= "=";
			}
		}
		// Prune out JS or PHP values
		if (preg_match("/^\\$\\{.*\\}/i", $value)) { 		// JS
			$endtrim 	= strlen($value) - 3;
			$value		= substr($value, 2, $endtrim);
			$value		= str_replace(';', '', $value);
		} else if (preg_match("/^\\?\\{.*\\}/i", $value)) {	// PHP
			$endtrim 	= strlen($value) - 3;
			$value 		= substr($value, 2, $endtrim);
			$value 		= '"'.eval("return " . $value).'"';
		} else {
			$value = '"'.$value.'"';
		}
									$out[] = '	so_' . $rand . '.addVariable("' . $name . '",' . $value . ');';
	}
	
									$out[] = '	so_' . $rand . '.write("' . $target . '");';
									$out[] = '';
									$out[] = '	// ]]>';
									$out[] = '</script>';
	// Add NoScript content
	if (!empty($noscript)) {
									$out[] = '<noscript>';
									$out[] = '	' . $noscript;
									$out[] = '</noscript>';
	}
									$out[] = '';
											
	$ret .= join("\n", $out);
	return $ret;
}
           
/***********************************************************************
*	Build a Satay Object for RSS feeds
************************************************************************/

function kml_flashembed_build_object_tag($atts) {
	
	$out	= array();	
	if (is_array($atts)) extract($atts);
	
	// Build a query string based on the $fvars attribute
	$querystring = (count($fvars) > 0) ? "?" : "";
	for ($i = 0; $i < count($fvars); $i++) {
		$thispair	= trim($fvars[$i]);
		$nvpair		= explode("=",$thispair);
		$name		= trim($nvpair[0]);
		$value		= "";
		for ($j = 1; $j < count($nvpair); $j++) {			// In case someone passes in a fvars with additional "="
			$value		.= trim($nvpair[$j]);
			$value		= preg_replace('/&#038;/', '&', $value);
			if ((count($nvpair) - 1)  != $j) {
				$value	.= "=";
			}
		}
		// Prune out JS or PHP values
		if (preg_match("/^\\$\\{.*\\}/i", $value)) { 		// JS
			$endtrim 	= strlen($value) - 3;
			$value		= substr($value, 2, $endtrim);
			$value		= str_replace(';', '', $value);
		} else if (preg_match("/^\\?\\{.*\\}/i", $value)) {	// PHP
			$endtrim 	= strlen($value) - 3;
			$value 		= substr($value, 2, $endtrim);
			$value 		= eval("return " . $value);
		}
		// else {
		//	$value = '"'.$value.'"';
		//}
		$querystring .= $name . '=' . $value;
		if ($i < count($fvars) - 1) {
			$querystring .= "&";
		}
	}
	
									$out[] = '';    
						  	  		$out[] = '<object	type="application/x-shockwave-flash"';
									$out[] = '			data="'.$movie.$querystring.'"'; 
	if (isset($base)) 	   		 	$out[] = '			base="'.$base.'"';
									$out[] = '			width="'.$width.'"';
									$out[] = '			height="'.$height.'">';
									$out[] = '	<param name="movie" value="' . $movie.$querystring . '" />';
	if (isset($play))				$out[] = '	<param name="play" value="' . $play . '" />';
	if (isset($loop))				$out[] = '	<param name="loop" value="' . $loop . '" />';
	if (isset($menu)) 				$out[] = '	<param name="menu" value="' . $menu . '" />';
	if (isset($scale)) 				$out[] = '	<param name="scale" value="' . $scale . '" />';
	if (isset($wmode)) 				$out[] = '	<param name="wmode" value="' . $wmode . '" />';
	if (isset($align)) 				$out[] = '	<param name="align" value="' . $align . '" />';
	if (isset($salign)) 			$out[] = '	<param name="salign" value="' . $salign . '" />';    
	if (isset($base)) 	   		 	$out[] = '	<param name="base" value="' . $base . '" />';
	if (isset($allowscriptaccess))	$out[] = '	<param name="allowScriptAccess" value="' . $allowscriptaccess . '" />';
	if (isset($allowfullscreen))	$out[] = '	<param name="allowFullScreen" value="' . $allowfullscreen . '" />';
	 								$out[] = '</object>';

	$ret .= join("\n", $out);
	return $ret;

}

/***********************************************************************
*	Trigger Function
************************************************************************/

function kmlDoObStart()
{
	ob_start('kml_flashembed');
}

/***********************************************************************
*	Add the calls to filters and anarchy.js
************************************************************************/

function kml_flashembed_add_flashobject_js() {
	global $anarchy_url,  $wp_db_version;
	echo '<script src="' . $anarchy_url . '/anarchy_media_player.php?anarchy.js" type="text/javascript"></script>';
}

if (preg_match("/(\/\?feed=|\/feed|\/wpmu-feed)/i",$_SERVER['REQUEST_URI'])) {
	// RSS Feeds
	$kml_request_type	= "feed";
} else {
	// Everything else
	$kml_request_type	= "nonfeed";
if (isset($wp_version)) {
	add_action('wp_head', 'kml_flashembed_add_flashobject_js');
	add_action('admin_menu', 'anarchy_add_options_page');
	add_action('edit_form_advanced', 'anarchy_videoquicktags_javascript');
	add_action('edit_page_form', 'anarchy_videoquicktags_javascript');
	add_filter("mce_external_plugins", "anarchy_mce_plugins", 0);
	add_filter("mce_buttons", "anarchy_mce_buttons", 0);
	if ( $wp_db_version < 6846 ) {
	add_action('tinymce_before_init','anarchy_external_plugins');
	}
	add_action('admin_footer', 'anarchy_add_quicktags');
	remove_filter('the_content', 'wptexturize');
	add_filter('the_content', 'vipers_videoquicktags_replacer', 11);
	add_filter('the_content', 'wptexturize', 12);
}
}
// Apply all over except the admin section
if (strpos($_SERVER['REQUEST_URI'], 'wp-admin') === false ) {
	add_action('template_redirect','kmlDoObStart');
}

/**
 * Return a plugin directory name.
 * @param path optional windows or unix filesystem path as a string
 * @return the plugin directory name as a string, without trailing slash
 */
function plugin_root($path = '') {
  if($path == '') $path = __FILE__;
  $s = preg_replace('/^.*wp-content[\\\\\/]plugins[\\\\\/]/', '', $path);
  str_replace('\\', '/', $s);
  $a = explode('/', $s);
  return $a[0];
}
?>