<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * This exception is thrown when a non-existent service is requested.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ehough_iconic_exception_ServiceNotFoundException extends ehough_iconic_exception_InvalidArgumentException
{
    private $id;
    private $sourceId;

    public function __construct($id, $sourceId = null, Exception $previous = null, array $alternatives = array())
    {
        if (null === $sourceId) {
            $msg = sprintf('You have requested a non-existent service "%s".', $id);
        } else {
            $msg = sprintf('The service "%s" has a dependency on a non-existent service "%s".', $sourceId, $id);
        }

        if ($alternatives) {
            if (1 == count($alternatives)) {
                $msg .= ' Did you mean this: "';
            } else {
                $msg .= ' Did you mean one of these: "';
            }
            $msg .= implode('", "', $alternatives).'"?';
        }

        if (version_compare(PHP_VERSION, '5.3') < 0) {

            parent::__construct($msg, 0);

        } else {

            parent::__construct($msg, 0, $previous);
        }

        $this->id = $id;
        $this->sourceId = $sourceId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSourceId()
    {
        return $this->sourceId;
    }
}
