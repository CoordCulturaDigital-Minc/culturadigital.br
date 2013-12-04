function tubepress_popup_player(galleryId, videoId) {
	var wrapperId = "#tubepress_embedded_object_" + galleryId,
		wrapper = jQuery(wrapperId),
		obj = jQuery(wrapperId + " > object"),
		params = obj.children("param"),
		win = window.open('', '', 
    		'location=0,directories=0,menubar=0,scrollbars=0,status=0,toolbar=0,width=' + obj.css("width") + ',height=' + obj.css("height")),
    	preamble = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\n<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html;charset=utf-8" /><title>' + 
    		jQuery("#tubepress_title_" + videoId + "_" + galleryId).html() + '</title></head><body style="margin: 0pt; background-color: black;">',
    	newHtml = TubePress.deepConstructObject(wrapper, params);
    
    win.document.write(preamble + newHtml + '</body></html>');
    win.document.close() ;
}

function tubepress_popup_player_init(baseUrl) {  }
