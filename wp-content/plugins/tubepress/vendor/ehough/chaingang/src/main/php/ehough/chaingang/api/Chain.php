<?php
/**
 * Copyright 2006 - 2013 Eric D. Hough (http://ehough.com)
 *
 * This file is part of chaingang (https://github.com/ehough/chaingang)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * This class is heavily based on the Commons Chain project.
 * http://commons.apache.org/chain
 *
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * A Chain represents a configured list of Commands that will be executed in order to
 * perform processing on a specified Context. Each included Command will be executed
 * in turn, until either one of them returns true, one of the executed Commands throws
 * an exception, or the end of the chain has been reached. The Chain itself will return
 * the return value of the last Command that was executed (if no exception was thrown),
 * or rethrow the thrown exception.
 *
 * Note that Chain extends Command, so that the two can be used interchangeably when
 * a Command is expected. This makes it easy to assemble workflows in a hierarchical
 * manner by combining subchains into an overall processing chain.
 */
interface ehough_chaingang_api_Chain extends ehough_chaingang_api_Command
{
    /**
     * Add a command to this chain.
     *
     * Add a Command to the list of Commands that will be called in turn when this Chain's
     * execute() method is called. Once execute() has been called at least once, it is no
     * longer possible to add additional Commands; instead, an exception will be thrown.
     *
     * @param ehough_chaingang_api_Command $command The Command to be added.
     *
     * @return void
     *
     * @throws ehough_chaingang_api_exception_IllegalStateException If this Chain has already
     *                                                              been executed at least once,
     *                                                              so no further configuration is allowed.
     */
    function addCommand(ehough_chaingang_api_Command $command);
}