tinyMCE.importPluginLanguagePack('anarchyvideo', '');
var TinyMCE_AnarchyMediaPlugin = {
	getInfo : function() {
		return {
			longname : 'Anarchy Media Sites Embed',
			author : 'An-archos',
			authorurl : 'http://an-archos.com',
			infourl : 'http://an-archos.com/anarchy-media-player',
			version : "1.0"
		};
	},
	getControlHTML : function(cn) {
		switch (cn) {
			case "anarchyvideo":
				var button = '<a id="mce_editor_0_anarchymedia" href="javascript:tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceAnarchyMediaInsert\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceAnarchyMediaInsert\');return false;" onmousedown="return false;" class="mceButtonNormal" target="_self"><img src="{$pluginurl}/images/media.gif" title="Embed media links, YouTube etc"></a>';
				return button;
		}
		return "";
	},
	execCommand : function(editor_id, element, command, user_interface, value) {
		switch (command) {
			case "mceAnarchyMediaInsert":
				edInsertVideoSite();
			return true;
		}
		return false;
	}
};
tinyMCE.addPlugin("anarchyvideo", TinyMCE_AnarchyMediaPlugin);