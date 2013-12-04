/**
 * @kos
 * Queue to handle the Live Traffic entries
 * @param $ The jQuery object
 * @param adminPostUrl
 * @param serverMethod
 * @param ajaxLoaderImageUrl
 * @param maxEntries Integer. Holds the max number of items to display in the Live Traffic window
 */
var liveTrafficQueue = function($, adminPostUrl, serverMethod, ajaxLoaderImageUrl, maxEntries)
{
    function _createLoader($){
        var imgPath = ajaxLoaderImageUrl;
        var text = "Loading data...";
        return $('<span id="ajaxLoaderRemove"><img src="'+imgPath+'" title="'+text+'" alt="'+text+'"/><span>'+text+'</span></span>');
    }
    function _showLoader($parentElement, $loader){ $parentElement.append($loader); }
    function _hideLoader(element) { element.remove(); }

    var loader = _createLoader($)
        ,$table = $("#wsdTrafficScanTable")
        ,$tbody = $('#the-list', $table)
        ,nonce = $table.attr("data-nonce")
        ,loaderWrapper = $('#loaderWrapper')
        // holds the last item's id from the list in order to know from where to retrieve the next chunk of data
        ,getFrom = 0;

    /**
     * @internal
     * Retrieve data using ajax
     */
    this.retrieveData = function()
    {
        _showLoader(loaderWrapper, loader);
        $.ajax({
            type : "post",
            dataType : "json",
            cache: false,
            url : adminPostUrl,
            data : {'action': serverMethod, 'nonce': nonce, 'getFrom': getFrom, 'maxEntries': maxEntries},
            success: function(response) {
                _hideLoader($('#ajaxLoaderRemove'));
                if(response && response.type == "success") {
                    if(response.data){
                        if(response.data.length > 0){
                            handleEntries(response.data);
                        }
                        else {
                            // get existing elements if any
                            if($('tr', $tbody).length > 0){
                                getFrom = parseInt($('tr', $tbody).first().attr('data-id'));
                                if(isNaN(getFrom)){
                                    getFrom = 0;
                                }
                            }
                            else { getFrom = 0; }
                        }
                    }
                    response = null;
                }
                else { alert("An error occurred while trying to load data. Please try again in a few seconds."); }
            }
        });
    }

    /**
     * @internal
     * @returns {*|jQuery}
     * Retrieve the last entry from the displayed list of items as a jQuery object
     */
    var getLastEntry = function() { return $('tr', $tbody).last(); };

    /**
     * @internal
     * @param data Array the list of items to process retrieved from server
     * Handles the retrieved list of items
     */
    var handleEntries = function(data)
    {
        var numElements = $('tr', $tbody).length;
        var retrievedItems = data.length;

        if(numElements == 0){
            $.each(data, function(i,v){
                $tbody.prepend(v);
                getFrom = parseInt( $('tr', $tbody).first().attr('data-id') );
            });
        }
        else if (numElements == 1){
            var e = $('tr', $tbody).first();
            var t = e.find('p').text().toLowerCase();
            if(t.indexOf('no data') > -1){
                e.remove();
                $.each(data, function(i,v){
                    $tbody.prepend(v);
                    getFrom = parseInt( $('tr', $tbody).first().attr('data-id') );
                });
            }
        }
        else if((numElements + retrievedItems) > maxEntries)
        {
            $.each(data, function(i,v){
                var e = getLastEntry().remove();
                $tbody.prepend(v);
                getFrom = parseInt( $('tr', $tbody).first().attr('data-id') );
            });
        }
        else {
            $.each(data, function(i,v){
                $tbody.prepend(v);
                getFrom = parseInt( $('tr', $tbody).first().attr('data-id') );
            });
        }
    };
};
