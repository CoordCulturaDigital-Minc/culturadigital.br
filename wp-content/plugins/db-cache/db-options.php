<?php

@include(DBC_PATH."/languages/en_US.php");
if (WPLANG != '') {
	$langfile = DBC_PATH . "/languages/".WPLANG.".php";
	if (file_exists($langfile)) include($langfile);
}

    if (file_exists(DBC_PATH."/config.ini")) {
    	$config = unserialize(@file_get_contents(DBC_PATH."/config.ini"));
    }else{
    	$config = unserialize(@file_get_contents(WP_CONTENT_DIR."/db-config.ini"));
    }
    //$config = unserialize(get_option('dbc_options'));
    if (!isset($config['timeout']) || intval($config['timeout']) == 0) 
    {
    	$config['timeout'] = 5;
    }else{
    	$config['timeout'] = intval($config['timeout']/60);
    }
    if (!isset($config['enabled']))
    {
    	$config['enabled'] = false;
    	$cache_enabled = false;
    }else
    	$cache_enabled = true;
    if (!isset($config['loadstat'])) $config['loadstat'] = $dbc_labels['loadstattemplate'];
    if (!isset($config['filter'])) $config['filter'] = "_posts|_postmeta";
		if (defined('DBC_DEBUG') && DBC_DEBUG) $config['debug'] = 1;


function dbc_request($name, $default=null) 
{
	if (!isset($_POST[$name])) 
		return $default;

	return $_POST[$name];
}

function dbc_field_checkbox($name, $label='', $tips='', $attrs='') {
    global $config;
    
    echo '<th scope="row">';
    echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
    echo '<td><input type="checkbox" ' . $attrs . ' name="options[' . $name . ']" value="1" ' . ($config[$name]!= null?'checked':'') . '/>';
    echo ' ' . $tips;
    echo '</td>';
}

function dbc_field_text($name, $label='', $tips='', $attrs='') {
    global $config;
    if (strpos($attrs, 'size') === false) $attrs .= 'size="30"';
    echo '<th scope="row">';
    echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
    echo '<td><input type="text" ' . $attrs . ' name="options[' . $name . ']" value="' .
    htmlspecialchars($config[$name]) . '"/>';
    echo ' ' . $tips;
    echo '</td>';
}

function dbc_field_textarea($name, $label='', $tips='', $attrs='') {
    global $config;

    if (strpos($attrs, 'cols') === false) $attrs .= 'cols="70"';
    if (strpos($attrs, 'rows') === false) $attrs .= 'rows="5"';

    echo '<th scope="row">';
    echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
    echo '<td><textarea wrap="off" ' . $attrs . ' name="options[' . $name . ']">' .
    htmlspecialchars($config[$name]) . '</textarea>';
    echo '<br />' . $tips;
    echo '</td>';
}

if (!class_exists('pcache')) include DBC_PATH."/db-functions.php";
if (isset($_POST['clear'])) {
	$db_cache = new pcache();
	$db_cache->storage = WP_CONTENT_DIR."/tmp";
	$db_cache->clean(false);
	echo "<div id=\"message\" class=\"updated fade\"><p>".$dbc_labels['cleaned']."</p></div>";
}elseif (isset($_POST['clearold'])) {
	$db_cache = new pcache();
	$db_cache->storage = WP_CONTENT_DIR."/tmp";
	$db_cache->clean();
	echo "<div id=\"message\" class=\"updated fade\"><p>".$dbc_labels['expiredcleaned']."</p></div>";
}

if (isset($_POST['save'])) 
{
	$saveconfig = $config = dbc_request('options');

	if (defined('DBC_DEBUG') && DBC_DEBUG) $saveconfig['debug'] = 1;
	if ($saveconfig['timeout'] == '' || !is_numeric($saveconfig['timeout'])) $config['timeout'] = 5;

	// Convert to seconds for save
	$saveconfig['timeout'] = intval($config['timeout']*60);

	if (!isset($saveconfig['filter'])) $saveconfig['filter'] = "";

	// Activate/deactivate caching
	if (!isset($config['enabled']) && $cache_enabled) dbc_disable();
	elseif ($config['enabled'] == 1 && !$cache_enabled) {
		if (!dbc_enable()) unset($config['enabled']);
	}

	//update_option('dbc_options', serialize($saveconfig)); 
	$file = fopen(WP_CONTENT_DIR."/db-config.ini", 'w+');
	if ($file) {
		fwrite($file, serialize($saveconfig));
		fclose($file);
		echo "<div id=\"message\" class=\"updated fade\"><p>".$dbc_labels['saved']."</p></div>";
	}else{
		echo "<div id=\"message\" class=\"error\"><p>".$dbc_labels['cantsave']."</p></div>";
	}

	if (file_exists(DBC_PATH."/config.ini")) @unlink(DBC_PATH."/config.ini");

}

?>
<div class="wrap">
<form method="post">
<h2>DB Cache</h2>
        
<h3><?php echo $dbc_labels['configuration']; ?></h3>
<table class="form-table">
	<tr valign="top">
		<?php dbc_field_checkbox('enabled', $dbc_labels['activate']); ?>
	</tr>
	<tr valign="top">
		<?php dbc_field_text('timeout', $dbc_labels['timeout'], $dbc_labels['timeout_desc'], 'size="5"'); ?>
	</tr>
</table>

<h3><?php echo $dbc_labels['additional']; ?></h3>
<table class="form-table">
	<tr valign="top">
		<?php dbc_field_text('filter', $dbc_labels['filter'],"<br/>".$dbc_labels['filterdescription'],'size="100"'); ?>
	</tr>
	<tr valign="top">
		<?php dbc_field_text('loadstat', $dbc_labels['loadstat'],"<br/>".$dbc_labels['loadstatdescription'],'size="100"'); ?>
	</tr>
</table>

<p class="submit">
	<input class="button" type="submit" name="save" value="<?php echo $dbc_labels['save']; ?>">  
	<input class="button" type="submit" name="clear" value="<?php echo $dbc_labels['clear']; ?>">
	<input class="button" type="submit" name="clearold" value="<?php echo $dbc_labels['clearold']; ?>">
</p>      
</form>
</div>