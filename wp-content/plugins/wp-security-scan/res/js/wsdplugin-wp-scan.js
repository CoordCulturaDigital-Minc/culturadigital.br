
var wpScan = function($,adminPostUrl, nonce)
{
    // ajax
    var _makeParams = function(/*Object*/ajaxParams)
    {
        return $.extend({'nonce' : nonce}, ajaxParams);
    };

    // retrieve the scan state from server
    var ajaxGetScanState = function($, params)
    {
        var result = null;
        $.ajax({
            type : "post",
            dataType : "json",
            cache: false,
            url : adminPostUrl,
            data : _makeParams(params),
            'async' : false,
            success: function(response) {
                if(response)
                {
                    if(response.type == "success") {
                        result = response.data;
                    }
                    else if(response == 'error'){ alert(response.data); }
                    response = null;
                }
                else { alert("An error has occurred while trying to retrieve the scan state. Please try again in a few seconds."); }
            }
        });
        return result;
    }

    // retrieve the scan progress from server
    var ajaxGetScanProgress = function($, params)
    {
        var result = null;
        $.ajax({
            type : "post",
            dataType : "json",
            cache: false,
            url : adminPostUrl,
            data : _makeParams(params),
            'async' : false,
            success: function(response) {
                if(response)
                {
                    if(response.type == "success") {
                        result = response.data;
                    }
                    else if(response == 'error'){ alert(response.data); }
                    response = null;
                }
                else { alert("An error has occurred while trying to retrieve the scan progress. Please try again in a few seconds."); }
            }
        });
        return result;
    }


    this.getScanState = function() { return ajaxGetScanState($, {'action':'ajaxGetScanState'}); };

    this.getScanProgress = function() { return ajaxGetScanProgress($, {'action':'ajaxGetScanProgress'}); };

};