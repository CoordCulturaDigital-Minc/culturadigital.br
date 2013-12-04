tinyMCE.importPluginLanguagePack('anarchyswf','');
var TinyMCE_AnarchySwfPlugin = {
	getInfo : function() {
		return {
			longname : 'Anarchy SWF Embed',
			author : 'An-archos',
			authorurl : 'http://an-archos.com',
			infourl : 'http://an-archos.com/anarchy-media-player',
			version : "1.0"
		};
	},
	getControlHTML : function(cn) {
		switch (cn) {
			case "anarchyswf":
				var button = '<a id="mce_editor_0_anarchyswf" href="javascript:tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceAnarchySwfInsert\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceAnarchySwfInsert\');return false;" onmousedown="return false;" class="mceButtonNormal" target="_self"><img src="{$pluginurl}/images/flash.gif" title="Embed Flash swf"></a>';
				return button;
			}
		return "";
	},
	execCommand : function(editor_id, element, command, user_interface, value) {
		switch (command) {
			case "mceAnarchySwfInsert":
				edInsertMedia('Flash SWF', 'swf', 'swf');
			return true;
		}
		return false;
	}
};
tinyMCE.addPlugin("anarchyswf", TinyMCE_AnarchySwfPlugin);