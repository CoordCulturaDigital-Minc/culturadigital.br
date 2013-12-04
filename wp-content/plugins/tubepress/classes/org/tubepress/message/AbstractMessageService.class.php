<?php
/**
 * Copyright 2006 - 2010 Eric D. Hough (http://ehough.com)
 * 
 * This file is part of TubePress (http://tubepress.org)
 * 
 * TubePress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * TubePress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with TubePress.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

function_exists('tubepress_load_classes')
    || require(dirname(__FILE__) . '/../../../tubepress_classloader.php');
tubepress_load_classes(array('org_tubepress_message_MessageService'));

/**
 * Shared message functionality for org_tubepress_message_MessageService implementations.
 * This class basically provides one additional layer of abstraction between
 * the code and the actual message in the .pot files.
 */
abstract class org_tubepress_message_AbstractMessageService implements org_tubepress_message_MessageService
{
    private $_msgs = array(
        'options-page-title'       => 'TubePress Options',
        'options-page-save-button' => 'Save', 
        'options-page-intro-text'  => 'Set default options for the plugin. Each option here can be overridden on a per page/post basis with TubePress shortcodes. See the <a href="http://tubepress.org/documentation">documentation</a> for more info. An asterisk (*) next to an option indicates it\'s only available with <a href="http://tubepress.org/features">TubePress Pro</a>.', 
        'options-page-donation'    => 'TubePress is free. But if you enjoy the plugin, and appreciate the hundreds of hours I\'ve spent developing and supporting it, please consider a donation. No amount is too small. Thanks!', 
        
        'options-category-title-gallery'  => 'Which videos?', 
        'options-category-title-display'  => 'Appearance',
        'options-category-title-embedded' => 'Embedded Player', 
        'options-category-title-meta'     => 'Meta Display',
        'options-category-title-feed'      => 'YouTube Feed', 
        'options-category-title-advanced' => 'Advanced', 
    
        'options-title-top_rated'         => 'Top rated videos from...', 
        'options-title-favorites'         => 'This YouTube user\'s "favorites"', 
        'options-title-recently_featured' => 'The latest "featured" videos on YouTube\'s homepage', 
        'options-title-mobile'            => 'Videos for mobile phones', 
        'options-title-playlist'          => 'This playlist', 
        'options-desc-playlist'           => 'Limited to 200 videos per playlist. Will usually look something like this: D2B04665B213AE35. Copy the playlist id from the end of the URL in your browser\'s address bar (while looking at a YouTube playlist). It comes right after the "p=". For instance: http://youtube.com/my_playlists?p=D2B04665B213AE35', 
        'options-title-most_viewed'       => 'Most-viewed videos from', 
        'options-title-most_linked'       => 'Most-linked videos', 
        'options-title-most_recent'       => 'Most-recently added videos', 
        'options-title-most_discussed'    => 'Most-discussed videos', 
        'options-title-most_responded'    => 'Most-responded to videos', 
        'options-title-views'             => 'Views', 
        'options-title-tag'               => 'YouTube search for...', 
        'options-title-user'              => 'Videos from this YouTube user', 
        'options-desc-tag'                => 'YouTube limits this mode to 1,000 results',
        
        'options-title-playerLocation'   => 'Play each video', 
        'options-title-descriptionLimit' => 'Maximum description length', 
        'options-desc-descriptionLimit'  => 'Maximum number of characters to display in video descriptions. Set to 0 for no limit.', 
        'options-title-thumbHeight'      => 'Height (px) of thumbs', 
        'options-desc-thumbHeight'       => 'Default (and maximum) is 90', 
        'options-title-thumbWidth'       => 'Width (px) of thumbs', 
        'options-desc-thumbWidth'        => 'Default (and maximum) is 120', 
        'options-title-relativeDates'    => 'Use relative dates', 
        'options-desc-relativeDates'     => 'e.g. "yesterday" instead of "November 3, 1980"', 
        'options-title-resultsPerPage'   => 'Videos per Page', 
        'options-desc-resultsPerPage'    => 'Default is 20. Maximum is 50', 
        'options-title-orderBy'          => 'Order videos by',
        'options-title-paginationAbove'  => 'Show pagination above thumbnails',
        'options-title-paginationBelow'  => 'Show pagination below thumbnails',
        'options-desc-paginationAbove'   => 'Only applies to galleries that span multiple pages',
        'options-desc-paginationBelow'   => 'Only applies to galleries that span multiple pages',
        'options-title-ajaxPagination'   => '<a href="http://wikipedia.org/wiki/Ajax_(programming)">Ajax</a>-enabled pagination',
     
        'options-title-autoplay'             => 'Auto-play videos', 
        'options-title-border'               => 'Show border', 
        'options-title-embeddedHeight'       => 'Max height (px)', 
        'options-desc-embeddedHeight'        => 'Default is 350', 
        'options-title-embeddedWidth'        => 'Max width (px)', 
        'options-desc-embeddedWidth'         => 'Default is 425', 
        'options-title-fullscreen'           => 'Allow fullscreen playback',
        'options-title-genie'                => 'Enhanced genie menu', 
        'options-desc-genie'                 => 'Show the genie menu, if present, when the mouse enters the video area (as opposed to only when the user pushes the "menu" button)', 
        'options-title-loop'                 => 'Loop', 
        'options-desc-loop'                  => 'Continue playing the video until the user stops it', 
        'options-title-playerColor'          => 'Main color',
        'options-desc-playerColor'           => 'Default is 999999',
        'options-title-playerHighlight'      => 'Highlight color',
        'options-desc-playerHighlight'       => 'Default is FFFFFF',
        'options-title-showRelated'          => 'Show related videos', 
        'options-desc-showRelated'           => 'Toggles the display of related videos after a video finishes',
        'options-title-showInfo'             => 'Show title and rating before video starts',
        'options-title-hd'                   => 'Show videos in high definition when available',
        'options-title-playerImplementation' => 'Implementation',
        'options-desc-playerImplementation'  => 'The brand of the embedded player. Default is "YouTube"',
        
        'options-title-author'      => 'Author', 
        'options-title-category'    => 'Category', 
        'options-title-description' => 'Description', 
        'options-title-id'          => 'ID', 
        'options-title-length'      => 'Length', 
        'options-title-rating'      => 'Rating', 
        'options-title-ratings'     => 'Ratings', 
        'options-title-tags'        => 'Keywords', 
        'options-title-title'       => 'Title', 
        'options-title-uploaded'    => 'Posted', 
        'options-title-url'         => 'URL', 
        'options-title-views'       => 'Views', 
        
        'options-title-dateFormat'           => 'Date format', 
        'options-desc-dateFormat'            => 'Set the textual formatting of date information for videos. See <a href="http://us.php.net/date">date</a> for examples.', 
        'options-title-debugging_enabled'    => 'Enable debugging', 
        'options-desc-debugging_enabled'     => 'If checked, anyone will be able to view your debugging information. This is a rather small privacy risk. If you\'re not having problems with TubePress, or you\'re worried about revealing any details of your TubePress pages, feel free to disable the feature.',
        'options-title-keyword'              => 'Shortcode keyword', 
        'options-desc-keyword'               => 'The word you insert (in plaintext, between square brackets) into your posts/pages to display a gallery.', 
        'options-title-randomize_thumbnails' => 'Randomize thumbnails', 
        'options-desc-randomize_thumbnails'  => 'Most videos come with several thumbnails. By selecting this option, each time someone views your gallery they will see the same videos with each video\'s thumbnail randomized', 
        
        'options-title-filter_racy'          => 'Filter "racy" content', 
        'options-desc-filter_racy'           => 'Don\'t show videos that may not be suitable for minors.', 
        'options-title-clientKey'            => 'YouTube API Client ID', 
        'options-desc-clientKey'             => 'YouTube will use this client ID for logging and debugging purposes if you experience a service problem on their end. You can register a new client ID <a href="http://code.google.com/apis/youtube/dashboard/">here</a>. Don\'t change this unless you know what you\'re doing.', 
        'options-title-developerKey'         => 'YouTube API Developer Key', 
        'options-desc-developerKey'          => 'YouTube will use this developer key for logging and debugging purposes if you experience a service problem on their end. You can register a new client ID and developer key <a href="http://code.google.com/apis/youtube/dashboard/">here</a>. Don\'t change this unless you know what you\'re doing.', 
        'options-title-cacheEnabled'         => 'Enable request cache', 
        'options-desc-cacheEnabled'          => 'Store YouTube responses locally for 1 hour. Each response is on the order of a few hundred KB, so leaving the cache enabled will significantly reduce load times for your galleries at the slight expense of freshness.',
        'options-title-embeddableOnly'       => 'Only retrieve embeddable videos',
        'options-desc-embeddableOnly'        => 'Some videos have embedding disabled. Checking this option will exclude these videos from your galleries.',
    
        'options-title-resultCountCap'	     => 'Maximum total videos to retrieve',
        'options-desc-resultCountCap'		 => 'This can help to reduce the number of pages in your gallery. Set to "0" to remove any limit.',
    
        'player-normal'      => 'normally (at the top of your gallery)', 
        'player-popup'       => 'in a popup window',
        'player-youtube'     => 'from the original YouTube page', 
        'player-shadowbox'   => 'with Shadowbox',
        'player-jqmodal'     => 'with jqModal',
        'player-tinybox'     => 'with TinyBox',
        'player-fancybox'    => 'with FancyBox',
        'player-static'      => 'statically (page refreshes on each thumbnail click)',
    
        'order-relevance'    => 'relevance', 
        'order-viewCount'    => 'view count', 
        'order-rating'       => 'rating', 
        'order-published'    => 'date published', 
        'order-random'       => 'randomly',
        'order-position'     => 'position in a playlist',
        'order-commentCount' => 'comment count',
        'order-duration'     => 'length',
        'order-title'        => 'title',
    
        'timeFrame-today'   => 'today', 
        'timeFrame-this_week'    => 'this week', 
        'timeFrame-this_month'   => 'this month', 
        'timeFrame-all_time' => 'all time', 
    
        'video-author'      => 'Author', 
        'video-category'    => 'Category', 
        'video-description' => 'Description', 
        'video-id'          => 'ID', 
        'video-length'      => 'Runtime', 
        'video-rating'      => 'Rating', 
        'video-ratings'     => 'Ratings', 
        'video-tags'        => 'Keywords', 
        'video-title'       => 'Title', 
        'video-uploaded'    => 'Posted', 
        'video-url'         => 'URL', 
        'video-views'       => 'Views', 
    
        'validation-int-type'       => '%s can only take on integer values. You supplied %s.',
        'validation-int-range'      => '"%s" must be between "%d" and "%d". You supplied "%d".',
        'validation-text'           => '%s must be a string. You supplied %s.',
        'validation-no-such-option' => '"%s" is not a valid option name.',
        'validation-bool'           => '"%s" must be either true or false. You supplied "%s".',
        'validation-enum'           => '"%s" must be one of "%s". You supplied "%s".',
        'validation-no-dots-in-template' => 'Template names cannot contain two consecutive periods',
    
        'next' => 'next', 
        'prev' => 'prev', 

        'widget-description'           => 'Displays YouTube videos in your sidebar using TubePress', 
        'widget-tagstring-description' => 'TubePress shortcode for the widget. See the <a href="http://tubepress.org/documentation"> documentation</a>.',
        
        'safeSearch-none'     => 'none',
        'safeSearch-moderate' => 'moderate',
        'safeSearch-strict'   => 'strict',

        'playerImplementation-youtube'    => 'YouTube',
        'playerImplementation-longtail'   => 'JW FLV Media Player (by Longtail Video)'
    );
    
    /**
     * Takes a message key and provides the actual message to translate
     *
     * @param string $msgId The message id
     *
     * @return string The message translation key for gettext
     */
    protected function _keyToMessage($msgId)
    {
        if (array_key_exists($msgId, $this->_msgs)) {
            return $this->_msgs[$msgId];     
        }
        return '';
    }
}
