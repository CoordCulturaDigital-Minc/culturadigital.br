<?php
/**
 * Copyright 2006 - 2013 TubePress LLC (http://tubepress.org)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Removes "PL" from the start of playlist values.
 */
class tubepress_addons_youtube_impl_listeners_options_YouTubePlaylistPlPrefixRemover
{
    /**
     * @var ehough_epilog_Logger
     */
    private $_logger;

    public function __construct()
    {
        $this->_logger = ehough_epilog_LoggerFactory::getLogger('YouTube Playlist PL Prefix Remover');
    }


    public function onPreValidationOptionSet(tubepress_api_event_EventInterface $event)
    {
        $name = $event->getArgument('optionName');

        /** We only care about playlistValue. */
        if ($name !== tubepress_addons_youtube_api_const_options_names_GallerySource::YOUTUBE_PLAYLIST_VALUE) {

            return;
        }

        $value = $event->getSubject();

        if (! is_string($value)) {

            return;
        }

        if (tubepress_impl_util_StringUtils::startsWith($value, 'PL')) {

            if ($this->_logger->isHandling(ehough_epilog_Logger::DEBUG)) {

                $this->_logger->debug(sprintf('Removing \'PL\' prefix from playlist value of %s', $value));
            }

            $newValue = tubepress_impl_util_StringUtils::replaceFirst('PL', '', $value);

            $event->setSubject($newValue);

            return;
        }

        if ($this->_logger->isHandling(ehough_epilog_Logger::DEBUG)) {

            $this->_logger->debug(sprintf('Playlist value %s does not beging with \'PL\'', $value));
        }
    }
}
