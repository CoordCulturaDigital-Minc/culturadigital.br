
var $jx = jQuery.noConflict();  
$jx(document).ready(function(){
     
	$jx('ul.spy').simpleSpy('4','4000');
	
			$jx('ul.spy li').reverseOrder(); 
	
	
});



(function ($jx) {
$jx.fn.reverseOrder = function() {
	return this.each(function() {
		$jx(this).prependTo( $jx(this).parent() );
	});
};

    
$jx.fn.simpleSpy = function (limit, interval) {
    limit = limit || 4;
    interval = interval || 4000;
    
    return this.each(function () {
        // 1. setup
            // capture a cache of all the Interesting title s
            // chomp the list down to limit li elements
        var $jxlist = $jx(this),
            items = [], // uninitialised
            currentItem = limit,
            total = 0, // initialise later on
            start = 0,//when the effect first starts
            startdelay = 4000;
            height = $jxlist.find('> li:first').height();
            
        // capture the cache
        $jxlist.find('> li').each(function () {
            items.push('<li>' + $jx(this).html() + '</li>');
        });
        
        total = items.length;
        
        $jxlist.wrap('<div class="spyWrapper" />').parent().css({ height : height * limit });

        $jxlist.find('> li').filter(':gt(' + (limit - 1) + ')').remove();

        // 2. effect        
        function spy() {
            // insert a new item with opacity and height of zero
            var $jxinsert = $jx(items[currentItem]).css({
                height : 0,
                opacity : 0,
                display : 'none'
            }).prependTo($jxlist);
                        
            // fade the LAST item out
            $jxlist.find('> li:last').animate({ opacity : 0}, 1000, function () {
                // increase the height of the NEW first item
                 $jxinsert.animate({ height : height }, 1000).animate({ opacity : 1 }, 1000);
                
                // AND at the same time - decrease the height of the LAST item
                // $jx(this).animate({ height : 0 }, 1000, function () {
                    // finally fade the first item in (and we can remove the last)
                    $jx(this).remove();
                // });
            });
            
            currentItem++;
            if (currentItem >= total) {
                currentItem = 0;
            }
            
            setTimeout(spy, interval)
        }
        
        if (start < 1) {
               setTimeout(spy,startdelay);
                start++;
            } else {
            spy();
            }
        
    });
};
    
})(jQuery);