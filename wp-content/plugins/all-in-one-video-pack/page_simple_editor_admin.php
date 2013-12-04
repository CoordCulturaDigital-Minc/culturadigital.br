<?php
	require('../../../wp-blog-header.php'); 
	define('WP_USE_THEMES', false);
	define('WP_ADMIN', TRUE);
	auth_redirect();
	require_once('settings.php');
	require_once('lib/kaltura_model.php');
	require_once('lib/kaltura_helpers.php');  
  
	if (!KalturaHelpers::userCanEdit())
	{
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	
	$entryId = @$_GET['entryId'];
	
	if (!$entryId)
	{
		wp_die(__('The video is missing or invalid.'));
	}

	$kmodel = KalturaModel::getInstance();
	$ks = $kmodel->getClientSideSession();
	if (!$ks)
	{
		wp_die(__('Failed to start new session.'));
	}
	
	$viewData["swfUrl"] 	= KalturaHelpers::getSimpleEditorUrl(KALTURA_KSE_UICONF);
	$viewData["flashVars"] 	= KalturaHelpers::getSimpleEditorFlashVars($ks, $entryId);
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo KalturaHelpers::getPluginUrl(); ?>/css/kaltura.css"/>
<style type="text/css">
	html, body { margin:0; padding:0; }
</style>
<script type="text/javascript" src="<?php echo KalturaHelpers::getPluginUrl(); ?>/js/swfobject.js"></script>
<script type="text/javascript" src="<?php echo KalturaHelpers::getPluginUrl(); ?>/js/kaltura.js"></script>
<script type="text/javascript" src="<?php echo KalturaHelpers::getPluginUrl(); ?>/../../../wp-includes/js/jquery/jquery.js"></script>
<?php 
	KalturaHelpers::addWPVersionJS();
?>
<script type="text/javascript">
	function onSimpleEditorSaveClick()
	{
		
	}
	
	function onSimpleEditorBackClick()
	{
		setTimeout("onSimpleEditorBackClickTimedout()", 0);
	}
	
	function onSimpleEditorBackClickTimedout() 
	{
		var topWindow = Kaltura.getTopWindow();
		
		// remove the unload event (causes errors in IE7)
		jQuery(window).unbind('unload');
		
		jQuery("#kaltura_simple_editor_wrapper").empty();
		
		Kaltura.restoreSimpleEditorHack();
		
		// restore the mac firefox opacity bug workaround
		if (Kaltura.isMacFF())
			Kaltura.showTinyMCEToolbar();
			
		topWindow.Kaltura.restoreModalSize(
			function () {
				topWindow.Kaltura.bindOverlayClick();
				window.location = '<?php echo urldecode($_GET["backurl"]); ?>';
			}
		);	
	}

	var topWindow = Kaltura.getTopWindow();

	topWindow.Kaltura.unbindOverlayClick();
	
	// fix for IE6, scroll the page up so modal would animate in the center of the window
	if (jQuery.browser.msie && jQuery.browser.version < 7)
		topWindow.scrollTo(0,0);
		
	// fix mac firefox opacity bug
	if (Kaltura.isMacFF())
		Kaltura.hideTinyMCEToolbar();
			
	jQuery(function () {
		Kaltura.hackSimpleEditorModal();

		// this is needed when the user clicks back on his browser
		jQuery(window).unload(function () {
			Kaltura.restoreSimpleEditorHack();

			// call the top window to do the animation
			topWindow.Kaltura.restoreModalSize();
			
			// restore the mac firefox opacity bug workaround
			if (Kaltura.isMacFF())
				Kaltura.showTinyMCEToolbar();
		})
	});
	
	topWindow.Kaltura.hackModalBoxWp26();
</script>

</head>
<body>
<?php
	require_once("view/view_simple_editor.php"); 
?>
</body>
</html>
