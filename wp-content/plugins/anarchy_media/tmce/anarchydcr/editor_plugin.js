tinyMCE.importPluginLanguagePack('anarchydcr','');
var TinyMCE_AnarchyDcrPlugin = {
	getInfo : function() {
		return {
			longname : 'Anarchy DCR Embed',
			author : 'An-archos',
			authorurl : 'http://an-archos.com',
			infourl : 'http://an-archos.com/anarchy-media-player',
			version : "1.0"
		};
	},
	getControlHTML : function(cn) {
		switch (cn) {
			case "anarchydcr":
				var button = '<a id="mce_editor_0_anarchydcr" href="javascript:tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceAnarchyDCRInsert\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceAnarchyDCRInsert\');return false;" onmousedown="return false;" class="mceButtonNormal" target="_self"><img src="{$pluginurl}/images/dir.gif" title="Embed Director dcr"></a>';
				return button;
		}
		return "";
	},
	execCommand : function(editor_id, element, command, user_interface, value) {
		switch (command) {
			case "mceAnarchyDCRInsert":
				edInsertMedia('Director', 'dcr', 'dcr');
			return true;
		}
		return false;
	}
};
tinyMCE.addPlugin("anarchydcr", TinyMCE_AnarchyDcrPlugin);