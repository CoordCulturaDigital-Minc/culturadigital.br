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
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ehough_finder_exception_ShellCommandFailureException extends ehough_finder_exception_AdapterFailureException
{
    /**
     * @var ehough_finder_shell_Command
     */
    private $command;

    /**
     * @param ehough_finder_adapter_AdapterInterface $adapter
     * @param ehough_finder_shell_Command          $command
     * @param Exception|null  $previous
     */
    public function __construct(ehough_finder_adapter_AdapterInterface $adapter, ehough_finder_shell_Command $command, Exception $previous = null)
    {
        $this->command = $command;
        parent::__construct($adapter, 'Shell command failed: "'.$command->join().'".', $previous);
    }

    /**
     * @return ehough_finder_shell_Command
     */
    public function getCommand()
    {
        return $this->command;
    }
}
