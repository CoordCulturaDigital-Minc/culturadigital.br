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
class ehough_finder_shell_Command
{
    /**
     * @var ehough_finder_shell_Command|null
     */
    private $parent;

    /**
     * @var array
     */
    private $bits = array();

    /**
     * @var array
     */
    private $labels = array();

    /**
     * @var callable|null
     */
    private $errorHandler;

    /**
     * Constructor.
     *
     * @param ehough_finder_shell_Command|null $parent Parent command
     */
    public function __construct(ehough_finder_shell_Command $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * Returns command as string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->join();
    }

    /**
     * Creates a new ehough_finder_shell_Command instance.
     *
     * @param ehough_finder_shell_Command|null $parent Parent command
     *
     * @return ehough_finder_shell_Command New ehough_finder_shell_Command instance
     */
    public static function create(ehough_finder_shell_Command $parent = null)
    {
        return new self($parent);
    }

    /**
     * Escapes special chars from input.
     *
     * @param string $input A string to escape
     *
     * @return string The escaped string
     */
    public static function escape($input)
    {
        return escapeshellcmd($input);
    }

    /**
     * Quotes input.
     *
     * @param string $input An argument string
     *
     * @return string The quoted string
     */
    public static function quote($input)
    {
        return escapeshellarg($input);
    }

    /**
     * Appends a string or a ehough_finder_shell_Command instance.
     *
     * @param string|ehough_finder_shell_Command $bit
     *
     * @return ehough_finder_shell_Command The current ehough_finder_shell_Command instance
     */
    public function add($bit)
    {
        $this->bits[] = $bit;

        return $this;
    }

    /**
     * Prepends a string or a command instance.
     *
     * @param string|ehough_finder_shell_Command $bit
     *
     * @return ehough_finder_shell_Command The current ehough_finder_shell_Command instance
     */
    public function top($bit)
    {
        array_unshift($this->bits, $bit);

        foreach ($this->labels as $label => $index) {
            $this->labels[$label] += 1;
        }

        return $this;
    }

    /**
     * Appends an argument, will be quoted.
     *
     * @param string $arg
     *
     * @return ehough_finder_shell_Command The current ehough_finder_shell_Command instance
     */
    public function arg($arg)
    {
        $this->bits[] = self::quote($arg);

        return $this;
    }

    /**
     * Appends escaped special command chars.
     *
     * @param string $esc
     *
     * @return ehough_finder_shell_Command The current ehough_finder_shell_Command instance
     */
    public function cmd($esc)
    {
        $this->bits[] = self::escape($esc);

        return $this;
    }

    /**
     * Inserts a labeled command to feed later.
     *
     * @param string $label The unique label
     *
     * @return ehough_finder_shell_Command The current ehough_finder_shell_Command instance
     *
     * @throws RuntimeException If label already exists
     */
    public function ins($label)
    {
        if (isset($this->labels[$label])) {
            throw new RuntimeException(sprintf('Label "%s" already exists.', $label));
        }

        $this->bits[] = self::create($this);
        $this->labels[$label] = count($this->bits)-1;

        return $this->bits[$this->labels[$label]];
    }

    /**
     * Retrieves a previously labeled command.
     *
     * @param string $label
     *
     * @return ehough_finder_shell_Command The labeled command
     *
     * @throws RuntimeException
     */
    public function get($label)
    {
        if (!isset($this->labels[$label])) {
            throw new RuntimeException(sprintf('Label "%s" does not exist.', $label));
        }

        return $this->bits[$this->labels[$label]];
    }

    /**
     * Returns parent command (if any).
     *
     * @return ehough_finder_shell_Command Parent command
     *
     * @throws RuntimeException If command has no parent
     */
    public function end()
    {
        if (null === $this->parent) {
            throw new RuntimeException('Calling end on root command doesn\'t make sense.');
        }

        return $this->parent;
    }

    /**
     * Counts bits stored in command.
     *
     * @return int The bits count
     */
    public function length()
    {
        return count($this->bits);
    }

    /**
     * @param mixed $errorHandler
     *
     * @return ehough_finder_shell_Command
     */
    public function setErrorHandler($errorHandler)
    {
        $this->errorHandler = $errorHandler;

        return $this;
    }

    /**
     * @return callable|null
     */
    public function getErrorHandler()
    {
        return $this->errorHandler;
    }

    /**
     * Executes current command.
     *
     * @return array The command result
     *
     * @throws RuntimeException
     */
    public function execute()
    {
        if (null === $this->errorHandler) {
            exec($this->join(), $output);
        } else {
            $process = proc_open($this->join(), array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $pipes);
            $output = preg_split('~(\r\n|\r|\n)~', stream_get_contents($pipes[1]), -1, PREG_SPLIT_NO_EMPTY);

            if ($error = stream_get_contents($pipes[2])) {
                call_user_func($this->errorHandler, $error);
            }

            proc_close($process);
        }

        return $output ? $output : array();
    }

    /**
     * Joins bits.
     *
     * @return string
     */
    public function join()
    {
        return implode(' ', array_filter(
            array_map(array($this, '_callbackJoinMap'), $this->bits),
            array($this, '_callbackJoinFilter')
        ));
    }

    public function _callbackJoinMap($bit)
    {
        return $bit instanceof ehough_finder_shell_Command ? $bit->join() : ($bit ? $bit : null);
    }

    public function _callbackJoinFilter($bit)
    {
        return null !== $bit;
    }

    /**
     * Insert a string or a ehough_finder_shell_Command instance before the bit at given position $index (index starts from 0).
     *
     * @param string|ehough_finder_shell_Command $bit
     * @param integer        $index
     *
     * @return ehough_finder_shell_Command The current ehough_finder_shell_Command instance
     */
    public function addAtIndex($bit, $index)
    {
        array_splice($this->bits, $index, 0, $bit);

        return $this;
    }
}
