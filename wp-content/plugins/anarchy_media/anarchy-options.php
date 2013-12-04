<?php
/*
Author: An-archos
Author URI: http://an-archos.com/anarchy-media-player
Description: Administrative options for Anarchy Media Player
*/
if(is_plugin_page() ) {
load_plugin_textdomain('anarchy',$path = 'wp-content/plugins/anarchy_media');
$location = get_option('siteurl') . '/wp-admin/options-general.php?page=anarchy_media/anarchy-options.php';

/************************ Update/Add options ************************/

if ('process' == $_POST['stage']) {

// Flush the POC cache when new options are added - uncomment if using poc-amp-config.php instead of config.php
	if (defined('POC_CACHE_4')) poc_cache_flush();

// Domains
	if($_POST['anarchy_accepted_domains1'] == '' and $accepted_domains1 == '') : /* Do nothing */ ;
	else : update_option('anarchy_accepted_domains1', $_POST['anarchy_accepted_domains1']);
	endif;
	if($_POST['anarchy_accepted_domains2'] == '' and $accepted_domains2 == '') : /* Do nothing */ ;
	else : update_option('anarchy_accepted_domains2', $_POST['anarchy_accepted_domains2']);
	endif;
	if($_POST['anarchy_accepted_domains3'] == '' and $accepted_domains3 == '') : /* Do nothing */ ;
	else : update_option('anarchy_accepted_domains3', $_POST['anarchy_accepted_domains3']);
	endif;

// Vid downloads and autohyperlinks
	update_option('anarchy_viddownloadLink', $_POST['anarchy_viddownloadLink']);
	update_option('anarchy_autohyperlinks', $_POST['anarchy_autohyperlinks']);

// RTE buttons
	if($_POST['anarchy_flashbutton'] == 'true' and $flashbutton == 'true') : /* Do nothing */ ;
	else :
	if($_POST['anarchy_flashbutton'] == '' and $flashbutton == 'true') : ($_POST['anarchy_flashbutton'] = 'false');
	endif;
	update_option('anarchy_flashbutton', $_POST['anarchy_flashbutton']);
	endif;

	update_option('anarchy_directorbutton', $_POST['anarchy_directorbutton']);

	if($_POST['anarchy_videobutton'] == 'true' and $videobutton == 'true') : /* Do nothing */ ;
	else :
	if($_POST['anarchy_videobutton'] == '' and $videobutton == 'true') : ($_POST['anarchy_videobutton'] = 'false');
	endif;
	update_option('anarchy_videobutton', $_POST['anarchy_videobutton']);
	endif;

// MP3
	update_option('anarchy_playerloop', $_POST['anarchy_playerloop']);
	update_option('anarchy_mp3downloadLink', $_POST['anarchy_mp3downloadLink']);
	if($_POST['anarchy_mp3imgmargin'] == $mp3imgmarginD and $mp3imgmargin == $mp3imgmarginD)  : /* Do nothing */ ; 
	else : update_option('anarchy_mp3imgmargin', $_POST['anarchy_mp3imgmargin']); 
	endif;
	if($_POST['anarchy_mp3playerstyle'] == $mp3playerstyleD and $mp3playerstyle == $mp3playerstyleD)  : /* Do nothing */ ; 
	else : update_option('anarchy_mp3playerstyle', $_POST['anarchy_mp3playerstyle']); 
	endif;
	if($_POST['anarchy_playerbg'] == $playerbgD and $playerbg == $playerbgD)  : /* Do nothing */ ; 
	else : update_option('anarchy_playerbg', $_POST['anarchy_playerbg']); 
	endif;
 	if($_POST['anarchy_playerleftbg'] == $playerleftbgD and $playerleftbg == $playerleftbgD)  : /* Do nothing */ ; 
	else : update_option('anarchy_playerleftbg', $_POST['anarchy_playerleftbg']); 
	endif;
	if($_POST['anarchy_playerrightbg'] == $playerrightbgD and $playerrightbg == $playerrightbgD)  : /* Do nothing */ ; 
	else : update_option('anarchy_playerrightbg', $_POST['anarchy_playerrightbg']); 
	endif;
	if($_POST['anarchy_playerrightbghover'] == $playerrightbghoverD and $playerrightbghover == $playerrightbghoverD)  : /* Do nothing */ ; 
	else : update_option('anarchy_playerrightbghover', $_POST['anarchy_playerrightbghover']); 
	endif;
	if($_POST['anarchy_playerlefticon'] == $playerlefticonD and $playerlefticon == $playerlefticonD)  : /* Do nothing */ ; 
	else : update_option('anarchy_playerlefticon', $_POST['anarchy_playerlefticon']); 
	endif;
	if($_POST['anarchy_playerrighticon'] == $playerrighticonD and $playerrighticon == $playerrighticonD)  : /* Do nothing */ ; 
	else : update_option('anarchy_playerrighticon', $_POST['anarchy_playerrighticon']); 
	endif;
	if($_POST['anarchy_playerrighticonhover'] == $playerrighticonhoverD and $playerrighticonhover == $playerrighticonhoverD)  : /* Do nothing */ ; 
	else : update_option('anarchy_playerrighticonhover', $_POST['anarchy_playerrighticonhover']); 
	endif;
	if($_POST['anarchy_playertext'] == $playertextD and $playertext == $playertextD)  : /* Do nothing */ ; 
	else : update_option('anarchy_playertext', $_POST['anarchy_playertext']); 
	endif;
	if($_POST['anarchy_playerslider'] == $playersliderD and $playerslider == $playersliderD)  : /* Do nothing */ ; 
	else : update_option('anarchy_playerslider', $_POST['anarchy_playerslider']); 
	endif;
	if($_POST['anarchy_playertrack'] == $playertrackD and $playertrack == $playertrackD)  : /* Do nothing */ ; 
	else : update_option('anarchy_playertrack', $_POST['anarchy_playertrack']); 
	endif;
	if($_POST['anarchy_playerloader'] == $playerloaderD and $playerloader == $playerloaderD)  : /* Do nothing */ ; 
	else : update_option('anarchy_playerloader', $_POST['anarchy_playerloader']); 
	endif;
	if($_POST['anarchy_playerborder'] == $playerborderD and $playerborder == $playerborderD)  : /* Do nothing */ ; 
	else : update_option('anarchy_playerborder', $_POST['anarchy_playerborder']); 
	endif;

// FLV
	if($_POST['anarchy_flvwidth'] == $flvwidthD || $_POST['anarchy_flvwidth'] == '' and $flvwidth == $flvwidthD )  : /* Do nothing */ ; 
	else : update_option('anarchy_flvwidth', $_POST['anarchy_flvwidth']); 
	endif;
	
	if($_POST['anarchy_flvheight'] == $flvheightD || $_POST['anarchy_flvheight'] == '' and $flvheight == $flvheightD )  : /* Do nothing */ ; 
	else : update_option('anarchy_flvheight', $_POST['anarchy_flvheight']); 
	endif;

	if($_POST['anarchy_flvfullscreen'] == 'true' and $flvfullscreen == 'true') : /* Do nothing */ ;
	else :
	if($_POST['anarchy_flvfullscreen'] == '') : ($_POST['anarchy_flvfullscreen'] = 'false');
	endif;
	update_option('anarchy_flvfullscreen', $_POST['anarchy_flvfullscreen']);
	endif;

// QT
	if($_POST['anarchy_qtwidth'] == $qtwidthD || $_POST['anarchy_qtwidth'] == '' and $qtwidth == $qtwidthD )  : /* Do nothing */ ; 
	else : update_option('anarchy_qtwidth', $_POST['anarchy_qtwidth']); 
	endif;
	
	if($_POST['anarchy_qtheight'] == $qtheightD || $_POST['anarchy_qtheight'] == '' and $qtheight == $qtheightD )  : /* Do nothing */ ; 
	else : update_option('anarchy_qtheight', $_POST['anarchy_qtheight']); 
	endif;

	update_option('anarchy_qtloop', $_POST['anarchy_qtloop']);

	update_option('anarchy_qtkiosk', $_POST['anarchy_qtkiosk']);

	if($_POST['anarchy_qtversion'] == $qtversionD || $_POST['anarchy_qtversion'] == '' and $qtversion == $qtversionD )  : /* Do nothing */ ;
	else : update_option('anarchy_qtversion', $_POST['anarchy_qtversion']);
	endif;

	if($_POST['anarchy_vidimgmargin'] == $vidimgmarginD and $vidimgmargin == $vidimgmarginD)  : /* Do nothing */ ;
	else : update_option('anarchy_vidimgmargin', $_POST['anarchy_vidimgmargin']);
	endif;

// WMV
	if($_POST['anarchy_wmvwidth'] == $wmvwidthD || $_POST['anarchy_wmvwidth'] == '' and $wmvwidth == $wmvwidthD )  : /* Do nothing */ ; 
	else : update_option('anarchy_wmvwidth', $_POST['anarchy_wmvwidth']); 
	endif;
	
	if($_POST['anarchy_wmvheight'] == $wmvheightD || $_POST['anarchy_wmvheight'] == '' and $wmvheight == $wmvheightD )  : /* Do nothing */ ; 
	else : update_option('anarchy_wmvheight', $_POST['anarchy_wmvheight']); 
	endif;

//Youtube
	if($_POST['anarchy_youtubewidth'] == $youtubewidthD || $_POST['anarchy_youtubewidth'] == '' and $youtubewidth == $youtubewidthD )  : /* Do nothing */ ; 
	else : update_option('anarchy_youtubewidth', $_POST['anarchy_youtubewidth']); 
	endif;
	
	if($_POST['anarchy_youtubeheight'] == $youtubeheightD || $_POST['anarchy_youtubeheight'] == '' and $youtubeheight == $youtubeheightD )  : /* Do nothing */ ; 
	else : update_option('anarchy_youtubeheight', $_POST['anarchy_youtubeheight']); 
	endif;

// Google Video
	if($_POST['anarchy_googlewidth'] == $googlewidthD || $_POST['anarchy_googlewidth'] == '' and $googlewidth == $googlewidthD )  : /* Do nothing */ ; 
	else : update_option('anarchy_googlewidth', $_POST['anarchy_googlewidth']); 
	endif;
	
	if($_POST['anarchy_googleheight'] == $googleheightD || $_POST['anarchy_googleheight'] == '' and $googleheight == $googleheightD )  : /* Do nothing */ ; 
	else : update_option('anarchy_googleheight', $_POST['anarchy_googleheight']); 
	endif;

// iFilm
	if($_POST['anarchy_ifilmwidth'] == $ifilmwidthD || $_POST['anarchy_ifilmwidth'] == '' and $ifilmwidth == $ifilmwidthD )  : /* Do nothing */ ; 
	else : update_option('anarchy_ifilmwidth', $_POST['anarchy_ifilmwidth']); 
	endif;
	
	if($_POST['anarchy_ifilmheight'] == $ifilmheightD || $_POST['anarchy_ifilmheight'] == '' and $ifilmheight == $ifilmheightD )  : /* Do nothing */ ; 
	else : update_option('anarchy_ifilmheight', $_POST['anarchy_ifilmheight']); 
	endif;

// DailyMotion
	if($_POST['anarchy_dailymotionwidth'] == $dailymotionwidthD || $_POST['anarchy_dailymotionwidth'] == '' and $dailymotionwidth == $dailymotionwidthD )  : /* Do nothing */ ; 
	else : update_option('anarchy_dailymotionwidth', $_POST['anarchy_dailymotionwidth']); 
	endif;
	
	if($_POST['anarchy_dailymotionheight'] == $dailymotionheightD || $_POST['anarchy_dailymotionheight'] == '' and $dailymotionheight == $dailymotionheightD )  : /* Do nothing */ ; 
	else : update_option('anarchy_dailymotionheight', $_POST['anarchy_dailymotionheight']); 
	endif;

// Metacafe
	if($_POST['anarchy_metacafewidth'] == $metacafewidthD || $_POST['anarchy_metacafewidth'] == '' and $metacafewidth == $metacafewidthD )  : /* Do nothing */ ; 
	else : update_option('anarchy_metacafewidth', $_POST['anarchy_metacafewidth']); 
	endif;
	
	if($_POST['anarchy_metacafeheight'] == $metacafeheightD || $_POST['anarchy_metacafeheight'] == '' and $metacafeheight == $metacafeheightD )  : /* Do nothing */ ; 
	else : update_option('anarchy_metacafeheight', $_POST['anarchy_metacafeheight']); 
	endif;

// MySpace
	if($_POST['anarchy_myspacewidth'] == $myspacewidthD || $_POST['anarchy_myspacewidth'] == '' and $myspacewidth == $myspacewidthD )  : /* Do nothing */ ; 
	else : update_option('anarchy_myspacewidth', $_POST['anarchy_myspacewidth']); 
	endif;
	
	if($_POST['anarchy_myspaceheight'] == $myspaceheightD || $_POST['anarchy_myspaceheight'] == '' and $myspaceheight == $myspaceheightD )  : /* Do nothing */ ; 
	else : update_option('anarchy_myspaceheight', $_POST['anarchy_myspaceheight']); 
	endif;

// Break
	if($_POST['anarchy_breakwidth'] == $breakwidthD || $_POST['anarchy_breakwidth'] == '' and $breakwidth == $breakwidthD )  : /* Do nothing */ ; 
	else : update_option('anarchy_breakwidth', $_POST['anarchy_breakwidth']); 
	endif;
	
	if($_POST['anarchy_breakheight'] == $breakheightD || $_POST['anarchy_breakheight'] == '' and $breakheight == $breakheightD )  : /* Do nothing */ ; 
	else : update_option('anarchy_breakheight', $_POST['anarchy_breakheight']); 
	endif;

// Revver
	if($_POST['anarchy_revverwidth'] == $revverwidthD || $_POST['anarchy_revverwidth'] == '' and $revverwidth == $revverwidthD )  : /* Do nothing */ ; 
	else : update_option('anarchy_revverwidth', $_POST['anarchy_revverwidth']); 
	endif;
	
	if($_POST['anarchy_revverheight'] == $revverheightD || $_POST['anarchy_revverheight'] == '' and $revverheight == $revverheightD )  : /* Do nothing */ ; 
	else : update_option('anarchy_revverheight', $_POST['anarchy_revverheight']); 
	endif;

	if($_POST['anarchy_revverID'] == $revverIDD || $_POST['anarchy_revverID'] == '' and $revverID == $revverIDD)  : /* Do nothing */ ; 
	else : update_option('anarchy_revverID', $_POST['anarchy_revverID']); 
	endif;

}

/************************ Load config for form fields ************************/

include('config.php');

/************************ Write options page ************************/

?>
<form name="update_anarchy" method="post" action="<?php echo $location ?>&amp;updated=true">
	<input type="hidden" name="stage" value="process" />
<!--  start boxes -->
	<div class="wrap">
		<h2>Anarchy Media Player Options | <a href="javascript:void(window.open('<?php echo $anarchy_url; ?>/amp-help.php','Player','resizable=yes,location=no,menubar=no,scrollbars=yes,status=no,toolbar=no,fullscreen=no,dependent=no,left=50,top=50,width=480,height=640'))">Usage</a> | <a href="http://an-archos.com/contact" target="_blank">Support</a></h2>
		<p><strong>Go To:</strong> <a href="#mp3">MP3</a> | <a href="#flv">FLV</a> | <a href="#qt">Quicktime</a> | <a href="#wmp">WMP</a> | <a href="#media">Media Players</a></p>
		<p><em>Delete any field to reset default value</em></p>
<!--************************ General **************************-->
		<p>
			<label><strong>Accepted Domains:</strong></label>
<?php
            echo '1. <input name="anarchy_accepted_domains1" type="text" id="anarchy_accepted_domains1" style="width:150px;" value="'.$accepted_domains1.'"/>';
          ?>
<?php
            echo '&nbsp;&nbsp;2. <input name="anarchy_accepted_domains2" type="text" id="anarchy_accepted_domains2" style="width:150px;" value="'.$accepted_domains2.'"/>';
          ?>
<?php
            echo '&nbsp;&nbsp;3. <input name="anarchy_accepted_domains3" type="text" id="anarchy_accepted_domains3" style="width:150px;" value="'.$accepted_domains3.'"/>';
          ?>
			<br />
			(Optional - Restrict Anarchy javascript use to your domains. Add root domain name minus 'http://' or 'www')
		</p>
		<p>
			<label><input name="anarchy_viddownloadLink" type="checkbox" id="anarchy_viddownloadLink" value="inline"
<?php if($viddownloadLink == 'inline') {?>
				checked="checked"
<?php } ?>
				/> <strong>Enable video download links</strong></label> for flv movies and Windows Media Player
		</p>
		<p>
			<label><input name="anarchy_autohyperlinks" type="checkbox" id="anarchy_autohyperlinks" value="true"
<?php if($autohyperlinks == 'true') {?>
				checked="checked"
<?php } ?>
				/> <strong>Enable autohyperlinks</strong></label> (disabled by default as it may conflict with some themes)
		</p>
<!--************************ RTE **************************-->
		<h3>Rich Text Editor</h3>
		<p>
			<label><input name="anarchy_flashbutton" type="checkbox" id="anarchy_flashbutton" value="true"
<?php if($flashbutton == 'true') {?>
				checked="checked"
<?php } ?>
				/> <strong>Enable SWF Embed button</strong></label> for embedding Flash movies and banners
		</p>
		<p>
			<label><input name="anarchy_directorbutton" type="checkbox" id="anarchy_directorbutton" value="true"
<?php if($directorbutton == 'true') {?>
				checked="checked"
<?php } ?>
				/> <strong>Enable Director Embed button</strong></label> for embedding dcr movies
		</p>
		<p>
			<label><input name="anarchy_videobutton" type="checkbox" id="anarchy_videobutton" value="true"
<?php if($videobutton == 'true') {?>
				checked="checked"
<?php } ?>
				/> <strong>Enable Media Embed button</strong></label> for embedding YouTube, Revver, Dailymotion, iMix etc.
		</p>
		<p class="submit">
			<input type="submit" name="Submit" value="Update Options &raquo;" />
		</p>
	</div>
<!--************************ MP3 **************************-->
	<div class="wrap" id="mp3">
		<h2>MP3</h2>
		<p>
			<label><input name="anarchy_playerloop" type="checkbox" id="anarchy_playerloop" value="true"
<?php if($playerloop == 'true') {?>
				checked="checked"
<?php } ?>
				/> <strong>Loop MP3 playback</strong></label>
		</p>
		<p>
			<label><input name="anarchy_mp3downloadLink" type="checkbox" id="anarchy_mp3downloadLink" value="inline"
<?php if($mp3downloadLink == 'inline') {?>
				checked="checked"
<?php } ?>
				/> <strong>Enable MP3 download links</strong></label>
		</p>
          <p><label>MP3 button image CSS margins:</label>
<?php
            echo '<input name="anarchy_mp3imgmargin" type="text" id="anarchy_mp3imgmargin" style="width:200px;" value="'.$mp3imgmargin.'"/>';
          ?></p>
          <p><label>MP3 player CSS styling:</label>
<?php
            echo '<input name="anarchy_mp3playerstyle" type="text" id="anarchy_mp3playerstyle" style="width:400px;" value="'.$mp3playerstyle.'"/>';
          ?></p>
		<h3>MP3 player colours</h3>
          <table width="100%" border="0" cellpadding="4">
          	<tbody>
          		<tr>
          		<td  style="text-align:right;" width="25%">
          		<label>Background:</label>
<?php
        	    echo '#<input name="anarchy_playerbg" type="text" id="anarchy_playerbg" style="width:70px;" value="'.$playerbg.'"/>';
        		  ?><br />
          		<label>Left background:</label>
<?php
        	    echo '#<input name="anarchy_playerleftbg" type="text" id="anarchy_playerleftbg" style="width:70px;" value="'.$playerleftbg.'"/>';
        		  ?><br />
          		<label>Right background:</label>
<?php
        	    echo '#<input name="anarchy_playerrightbg" type="text" id="anarchy_playerrightbg" style="width:70px;" value="'.$playerrightbg.'"/>';
        		  ?><br />
          		<label>Right background hover:</label>
<?php
        	    echo '#<input name="anarchy_playerrightbghover" type="text" id="anarchy_playerrightbghover" style="width:70px;" value="'.$playerrightbghover.'"/>';
        		  ?> </td> 
          <td style="text-align:right;" width="25%"> <label>Left icon:</label> 
             <?php 
        	    echo '#<input name="anarchy_playerlefticon" type="text" id="anarchy_playerlefticon" style="width:70px;" value="'.$playerlefticon.'"/>';
        		  ?> 
             <br /> 
             <label>Right icon:</label> 
             <?php 
        	    echo '#<input name="anarchy_playerrighticon" type="text" id="anarchy_playerrighticon" style="width:70px;" value="'.$playerrighticon.'"/>';
        		  ?> 
             <br /> 
             <label>Right icon hover:</label> 
             <?php 
        	    echo '#<input name="anarchy_playerrighticonhover" type="text" id="anarchy_playerrighticonhover" style="width:70px;" value="'.$playerrighticonhover.'"/>';
        		  ?> 
             <br /> 
             <label>Text:</label> 
             <?php 
        	    echo '#<input name="anarchy_playertext" type="text" id="anarchy_playertext" style="width:70px;" value="'.$playertext.'"/>';
        		  ?><br />
       		</td>
        		<td style="text-align:right;" width="25%">
          		<label>Slider:</label>
<?php
        	    echo '#<input name="anarchy_playerslider" type="text" id="anarchy_playerslider" style="width:70px;" value="'.$playerslider.'"/>';
        		  ?><br />
          		<label>Loader bar:</label>
<?php
        	    echo '#<input name="anarchy_playertrack" type="text" id="anarchy_playertrack" style="width:70px;" value="'.$playertrack.'"/>';
        		  ?><br />
          		<label>Progress bar:</label>
<?php
        	    echo '#<input name="anarchy_playerloader" type="text" id="anarchy_playerloader" style="width:70px;" value="'.$playerloader.'"/>';
        		  ?><br />
          		<label>Progress border:</label>
<?php
        	    echo '#<input name="anarchy_playerborder" type="text" id="anarchy_playerborder" style="width:70px;" value="'.$playerborder.'"/>';
        		  ?>
        		</td>
        		<td width="25%">
				<embed style="margin: <?php echo $mp3playerstyle; ?>" src="<?php echo $anarchy_url; ?>/player.swf" flashvars="bg=0x<?php echo $playerbg; ?>&amp;leftbg=0x<?php echo $playerleftbg; ?>&amp;rightbg=0x<?php echo $playerrightbg; ?>&amp;rightbghover=0x<?php echo $playerrightbghover; ?>&amp;lefticon=0x<?php echo $playerlefticon; ?>&amp;righticon=0x<?php echo $playerrighticon; ?>&amp;righticonhover=0x<?php echo $playerrighticonhover; ?>&amp;text=0x<?php echo $playertext; ?>&amp;slider=0x<?php echo $playerslider; ?>&amp;track=0x<?php echo $playertrack; ?>&amp;loader=0x<?php echo $playerloader; ?>&amp;border=0x<?php echo $playerborder; ?>&amp;autostart=no&amp;loop=yes&amp;soundFile=http://music.hiddenshoal.com/wp-content/uploads/Media/HSR_Radio/enargeia/UFO/02-Teslas-Interlude.mp3" quality="high" wmode="transparent" name="player" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" align="middle" height="24" width="290">
				</td>
        		</tr>
        	</tbody>
        </table>
		<p class="submit">
			<input type="submit" name="Submit" value="Update Options &raquo;" />
		</p>

	</div>
<!--************************ FLV **************************-->
	<div class="wrap" id="flv">
		<h2>FLV</h2>
		<p><label>Width:</label>
<?php
            echo '<input name="anarchy_flvwidth" type="text" id="anarchy_flvwidth" style="width:40px;" value="'.$flvwidth.'"/>';
          ?>
		px&nbsp;&nbsp; <label>Height:</label>
<?php
            echo '<input name="anarchy_flvheight" type="text" id="anarchy_flvheight" style="width:40px;" value="'.$flvheight.'"/>';
          ?>
          px (allow 20px for controller)</p>
		<p>
			<label><input name="anarchy_flvfullscreen" type="checkbox" id="anarchy_flvfullscreen" value="true"
<?php if($flvfullscreen == 'true') {?>
				checked="checked"
<?php } ?>
				/> <strong>Enable FLV player fullscreen</strong></label>
		</p>
	</div>
<!--************************ Quicktime **************************-->
	<div class="wrap" id="qt">
		<h2>Quicktime</h2>
		<p><label>Width:</label>
<?php
            echo '<input name="anarchy_qtwidth" type="text" id="anarchy_qtwidth" style="width:40px;" value="'.$qtwidth.'"/>';
          ?>
		px&nbsp;&nbsp; <label>Height:</label>
<?php
            echo '<input name="anarchy_qtheight" type="text" id="anarchy_qtheight" style="width:40px;" value="'.$qtheight.'"/>';
          ?>
          px (allow 16px for controller)</p>

		<p>
			<label><input name="anarchy_qtloop" type="checkbox" id="anarchy_qtloop" value="true"
<?php if($qtloop == 'true') {?>
				checked="checked"
<?php } ?>
				/> <strong>Loop Quicktime playback</strong></label>
		</p>
		<p>
			<label><input name="anarchy_qtkiosk" type="checkbox" id="anarchy_qtkiosk" value="false"
<?php if($qtkiosk == 'false') {?>
				checked="checked"
<?php } ?>
				/> <strong>Enable Quicktime player 'Save as...'</strong></label> to download QT movies
		</p>
		<p><label>QuickTime version:</label>
<?php
            echo '<input name="anarchy_qtversion" type="text" id="anarchy_qtversion" style="width:20px;" value="'.$qtversion.'"/>';
          ?>
          (set the minimum version for your QT player)</p>
          <p><label>Quicktime poster image CSS margins:</label>
<?php
            echo '<input name="anarchy_vidimgmargin" type="text" id="anarchy_vidimgmargin" style="width:80px;" value="'.$vidimgmargin.'"/>';
          ?></p>

	</div>
<!--************************ WMV **************************-->
	<div class="wrap" id="wmp">
		<h2>Windows Media Player</h2>
		<p><label>Width:</label>
<?php
            echo '<input name="anarchy_wmvwidth" type="text" id="anarchy_wmvwidth" style="width:40px;" value="'.$wmvwidth.'"/>';
          ?>
		px&nbsp;&nbsp; <label>Height:</label>
<?php
            echo '<input name="anarchy_wmvheight" type="text" id="anarchy_wmvheight" style="width:40px;" value="'.$wmvheight.'"/>';
          ?>
          px (allow 46px for controller)</p>
		<p class="submit">
			<input type="submit" name="Submit" value="Update Options &raquo;" />
		</p>
	</div>

<!--************************ Youtube **************************-->
	<div class="wrap" id="media">
		<h2>Media players</h2>
		<p><em>Delete any field to reset default value</em></p>
		<h3>YouTube</h3>
		<label>Width:</label>
<?php
            echo '<input name="anarchy_youtubewidth" type="text" id="anarchy_youtubewidth" style="width:40px;" value="'.$youtubewidth.'"/>';
          ?>
		px&nbsp;&nbsp; <label>Height:</label>
<?php
            echo '<input name="anarchy_youtubeheight" type="text" id="anarchy_youtubeheight" style="width:40px;" value="'.$youtubeheight.'"/>';
          ?>
          px
<!--************************ Google **************************-->
		<h3>Google Video</h3> <label>Width:</label>
<?php
            echo '<input name="anarchy_googlewidth" type="text" id="anarchy_googlewidth" style="width:40px;" value="'.$googlewidth.'"/>';
          ?>
		px&nbsp;&nbsp; <label>Height:</label>
<?php
            echo '<input name="anarchy_googleheight" type="text" id="anarchy_googleheight" style="width:40px;" value="'.$googleheight.'"/>';
          ?>
          px
<!--************************ iFilm **************************-->
		<h3>iFilm</h3> <label>Width:</label>
<?php
            echo '<input name="anarchy_ifilmwidth" type="text" id="anarchy_ifilmwidth" style="width:40px;" value="'.$ifilmwidth.'"/>';
          ?>
		px&nbsp;&nbsp; <label>Height:</label>
<?php
            echo '<input name="anarchy_ifilmheight" type="text" id="anarchy_ifilmheight" style="width:40px;" value="'.$ifilmheight.'"/>';
          ?>
          px
<!--************************ DailyMotion **************************-->
		<h3>DailyMotion</h3> <label>Width:</label>
<?php
            echo '<input name="anarchy_dailymotionwidth" type="text" id="anarchy_dailymotionwidth" style="width:40px;" value="'.$dailymotionwidth.'"/>';
          ?>
		px&nbsp;&nbsp; <label>Height:</label>
<?php
            echo '<input name="anarchy_dailymotionheight" type="text" id="anarchy_dailymotionheight" style="width:40px;" value="'.$dailymotionheight.'"/>';
          ?>
          px
<!--************************ Metacafe **************************-->
		<h3>Metacafe</h3> <label>Width:</label>
<?php
            echo '<input name="anarchy_metacafewidth" type="text" id="anarchy_metacafewidth" style="width:40px;" value="'.$metacafewidth.'"/>';
          ?>
		px&nbsp;&nbsp; <label>Height:</label>
<?php
            echo '<input name="anarchy_metacafeheight" type="text" id="anarchy_metacafeheight" style="width:40px;" value="'.$metacafeheight.'"/>';
          ?>
          px
<!--************************ MySpace **************************-->
		<h3>MySpace</h3> <label>Width:</label>
<?php
            echo '<input name="anarchy_myspacewidth" type="text" id="anarchy_myspacewidth" style="width:40px;" value="'.$myspacewidth.'"/>';
          ?>
		px&nbsp;&nbsp; <label>Height:</label>
<?php
            echo '<input name="anarchy_myspaceheight" type="text" id="anarchy_myspaceheight" style="width:40px;" value="'.$myspaceheight.'"/>';
          ?>
          px
<!--************************ Break **************************-->
		<h3>Break.com</h3> <label>Width:</label>
<?php
            echo '<input name="anarchy_breakwidth" type="text" id="anarchy_breakwidth" style="width:40px;" value="'.$breakwidth.'"/>';
          ?>
		px&nbsp;&nbsp; <label>Height:</label>
<?php
            echo '<input name="anarchy_breakheight" type="text" id="anarchy_breakheight" style="width:40px;" value="'.$breakheight.'"/>';
          ?>
          px
<!--************************ Revver **************************-->
		<h3>Revver</h3> <label>Width:</label>
<?php
            echo '<input name="anarchy_revverwidth" type="text" id="anarchy_revverwidth" style="width:40px;" value="'.$revverwidth.'"/>';
          ?>
		px&nbsp;&nbsp; <label>Height:</label>
<?php
            echo '<input name="anarchy_revverheight" type="text" id="anarchy_revverheight" style="width:40px;" value="'.$revverheight.'"/>';
          ?>
		px&nbsp;&nbsp; <label>Affiliate ID:</label>
<?php
            echo '<input name="anarchy_revverID" type="text" id="anarchy_revverID" style="width:50px;" value="'.$revverID.'"/>';
          ?>
		<p class="submit">
			<input type="submit" name="Submit" value="Update Options &raquo;" />
		</p>
</div>
</form>
<?php }
?>
