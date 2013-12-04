<?php
/**
 * Copyright 2013 Eric D. Hough (http://ehough.com)
 *
 * This file is part of shortstop (https://github.com/ehough/shortstop)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Registry of all HTTP events. They are listed roughly in the order in which they occur.
 */
class ehough_shortstop_api_Events
{
    /**
     * Fired immediately before a request is executed.
     *
     * @subject ehough_shortstop_api_HttpRequest The request about to be executed.
     */
    const REQUEST = 'ehough.shortstop.request';

    /**
     * Fired when an HTTP transport is selected. Allows event listeners to veto the selection.
     *
     * @subject ehough_shortstop_impl_exec_command_AbstractHttpExecutionCommand The selected transport instance.
     *
     * @arg ok      boolean                          Set to false to veto this selection.
     * @arg request ehough_shortstop_api_HttpRequest The request about to be executed.
     */
    const TRANSPORT_SELECTED = 'ehough.shortstop.exec.transport.selected';

    /**
     * Fired after an HTTP transport is initialized to handle a request.
     *
     * @subject ehough_shortstop_impl_exec_command_AbstractHttpExecutionCommand The selected transport instance.
     *
     * @arg request ehough_shortstop_api_HttpRequest
     */
    const TRANSPORT_INITIALIZED = 'ehough.shortstop.exec.transport.initialized';

    /**
     * Fired after an HTTP transport successfully handles a request.
     *
     * @subject ehough_shortstop_impl_exec_command_AbstractHttpExecutionCommand The selected transport instance.
     *
     * @arg request  ehough_shortstop_api_HttpRequest
     * @arg response ehough_shortstop_api_HttpResponse
     */
    const TRANSPORT_SUCCESS = 'ehough.shortstop.exec.transport.success';

    /**
     * Fired after an HTTP transport hits an error.
     *
     * @subject ehough_shortstop_impl_exec_command_AbstractHttpExecutionCommand The selected transport instance.
     *
     * @arg request            ehough_shortstop_api_HttpRequest  The requested that was executed.
     * @arg response           ehough_shortstop_api_HttpResponse The HTTP response (may be null!)
     * @arg exception          Exception                         The exception thrown from the transport.
     * @arg rethrow            boolean                           OK to rethrow this exception (bubble?) or simply quietly indicate the transport failed.
     * @arg tryOtherTransports boolean                           OK to try other transports in the chain?
     */
    const TRANSPORT_FAILURE = 'ehough.shortstop.exec.transport.failure';

    /**
     * Fired after an HTTP transport is deconstructed.
     *
     * @subject ehough_shortstop_impl_exec_command_AbstractHttpExecutionCommand The selected transport instance.
     *
     * @arg request       ehough_shortstop_api_HttpRequest The requested that was executed.
     * @arg becaseOfError boolean                          Is the transport being torn down because of an error?
     */
    const TRANSPORT_TORNDOWN = 'ehough.shortstop.exec.transport.torndown';

    /**
     * Fired when a response is returned.
     *
     * @subject ehough_shortstop_api_HttpResponse The HTTP response.
     *
     * @arg request ehough_shortstop_api_HttpRequest The requested that was executed.
     */
    const RESPONSE = 'ehough.shortstop.response';
}