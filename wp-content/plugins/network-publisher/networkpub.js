var networkpub_lastpos=0;
jQuery(document).ready(function($){
	$(".networkpublisherremove").bind("click", function(e) {
		$("#networkpub_remove").html('<div class="updated fade" style="padding:5px;text-align:center">'+$("#networkpub_text_removing").val()+'.</div>')
		var key = $(this).attr("id");
		$(this).parent().parent().css('opacity','.30');
		if(key) {
			var blog_url = $("#networkpub_plugin_url").val();
			$.post(blog_url+"networkpub_ajax.php", {action:"networkpub_remove", networkpub_key:key}, function(data) {
				if(data != key) {
					$("#networkpub_remove").html('<div class="updated fade" style="padding:5px;text-align:center">'+$("#networkpub_text_an_error_occured").val()+': <a href="http://www.linksalpha.com/publisher/pubs">'+$("#networkpub_text_linksalpha_publisher").val()+'</a></div>');
				} else {
					var dr = data.split("_");
					$("#r_key_"+dr[1]).remove();
					$("#networkpub_remove").html('<div class="updated fade" style="padding:5px;text-align:center">'+$("#networkpub_text_publication_has_been_removed").val()+'</div>')
				}
		    });
		} 
		return false;
	});
	$.receiveMessage(
		function(e){
			$("#networkpub_postbox").height(e.data.split("=")[1]+'px');
		},
		'http://www.linksalpha.com'
	);
	$("#site_links").live("change", function(event) {
		$.postMessage(
			$(this).val(),
			'http://www.linksalpha.com/post',
			parent
		);
	});
	setTimeout(function(){
		if($("#linksalpha_browser").length>0){
			if($("#linksalpha_post_extension_chrome").length == 0) {
				if($("#linksalpha_browser").val() == 'chrome') {
					$("#linksalpha_post_download_chrome").show();
				} else if($("#linksalpha_browser").val() == 'firefox') {
					$("#linksalpha_post_download_firefox").show();
				} else if($("#linksalpha_browser").val() == 'safari') {
					$("#linksalpha_post_download_safari").show();
				}
			} else {
				$("#linksalpha_post_download_chrome").remove();
				$("#linksalpha_post_download_firefox").remove();
				$("#linksalpha_post_download_safari").remove();
				$(".networkpublisher_post_meta_box_first").css('border-top-color', 'transparent');
			}
		}
	},3000);
	if($("#networkpub_post_update").length) {
		$("#networkpub_post_update").live("click", function() {
			$("body").append('<div id="networkpub_overlay"><iframe id="linksalpha_post_plugin" src="http://www.linksalpha.com/post2/postpopup?'+$("#networkpub_post_data").val()+'" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="100%" scrolling="no"></iframe></div>');
			return false;
		});
	}
	$.receiveMessage(
		function(e){
			if(e.data=='["close"]') {
				$("#networkpub_overlay").remove();
			}
		},
		'http://www.linksalpha.com'
	);
});