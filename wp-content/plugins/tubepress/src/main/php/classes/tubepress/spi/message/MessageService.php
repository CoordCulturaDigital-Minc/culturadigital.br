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
 * General purpose message abstraction for TubePress
 */
interface tubepress_spi_message_MessageService
{
    const _ = 'tubepress_spi_message_MessageService';

    /**
     * Get the message corresponding to the given key.
     *
     * @param string $messageKey The message key.
     *
     * @return string The corresponding message.
     */
    function _($messageKey);
}
