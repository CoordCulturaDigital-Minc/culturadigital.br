/**
 * Copyright 2006 - 2014 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
var TubePressIframeLoader = (function () {

    'use strict';

    var loadIframe = function (rawBox) {

            var box     = jQuery(rawBox),
                url     = box.data('src'),
                iframes = jQuery('<iframe />'),
                iframe  = iframes[0],
                doc     = null;

            box.append(iframe);

            doc = iframe.contentWindow.document;

            doc.open().write('<body onload="var d = document;d.getElementsByTagName(\'head\')[0].appendChild(d.createElement(\'script\')).src=\'' + url.replace(/\//g, '\\/') + '\'">');

            doc.close();
        },

        init = function () {

            var boxes = jQuery('div.has-iframe');

            jQuery.each(boxes, function (index, value) {

                loadIframe(value);
            });
        };

    return { init : init };

}());

jQuery(function () {

    'use strict';

    TubePressIframeLoader.init();
});