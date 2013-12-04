<?php header('Content-type: text/javascript'); ?>
<?php require_once('../../../wp-blog-header.php'); ?>

$(function(){
	var paged = <?php echo $_GET['paged']; ?>;
	var maxpages = <?php echo $_GET['maxpages']; ?>;
	if(paged==maxpages)$("#more").css({display: 'none'});
	$('#more').click(function() {
		paged++;
		var uri = './?paged=' + paged;
		uri += (getParameterByName('s') != '') ? '&s=' + getParameterByName('s') : '';
		uri += '&sidebar=false';
		$(this).text('Loading...');
		$.ajax({
			url : uri,
			complete : function(data) {},
			success : function(data,status) {
				var foo = $(data).find('.post');
				$(foo).each(function(n){
				});
				$(foo).hide().appendTo('.posts').fadeIn();
				$('#more').text('more');
				if(paged==maxpages) {
					$('#more').css({display: 'none'});
				}
				$.scrollTo($(foo),700);
			},
			error : function(data,status) {}
		});
	});
	
	var commentlist = $('.commentlist').find('.comment');
	if(commentlist.length>10) $('.showall').fadeIn();
	$(commentlist).each(function(n) {
		if(n>9) $(this).hide();
	});
	$('.showall').click(function(){
		$(commentlist).each(function(n) {
			if(n>9) $(this).show();
		});
		
		$(this).fadeOut('slow');
	});
	
	<?php if(isset($_GET['status'])) : ?>
		$('#latest-status').text("<?php echo urldecode($_GET['status']); ?>");
		$('#latest-status').fadeIn('slow');
	<?php else : ?>
		$('#latest-status').fadeIn('fast');
	<?php endif; ?>
	
	$('#status').keyup(function(){
		$(this).val($(this).val().substr(0,140));
		var n = 140 - $(this).val().length;
		$('#status-field-char-counter').text(n);
		if(n>20) $('#status-field-char-counter').css({color:'#ccc'});
		if(n<20) $('#status-field-char-counter').css({color:'#440001'});
		if(n<10) $('#status-field-char-counter').css({color:'#cc2222'});
	});
	
	$('#sidebar_search_submit').click(function(){
		$('#sidebar_search').submit();
	});
	
	$('.single .reply a').click(function(){
		$.scrollTo($('#respond'),300);
	});
	$('#back-top').click(function(){
		$.scrollTo(0,400);
	});
	
	$('.collapsible').click(function() {
		$(this).next("ul").slideToggle(200);
		$(this).next("div").slideToggle(200);
		if($(this).attr('class').indexOf('collapsed')==-1) {
			$(this).addClass( ' collapsed' );
			$.cookie( $(this).attr( 'rel' ), ' collapsed' );
		} else {
			$(this).removeClass( ' collapsed' );
			$.cookie( $(this).attr( 'rel' ), null );
		}
	});
	$('.collapsible').each(function(n) {
		$(this).attr('rel','section_'+(n+1));
		if($.cookie($(this).attr('rel'))) {
			$(this).addClass(' collapsed');
			$(this).next("ul").slideToggle(20);
			$(this).next("div").slideToggle(20);
		}
	});
	
	function getParameterByName( name ) {
		name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
		var regexS = "[\\?&]"+name+"=([^&#]*)";
		var regex = new RegExp( regexS );
		var results = regex.exec( window.location.href );
		if( results == null )
		return "";
		else
		return results[1];
	}
});