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
 * Base exception for all adapter failures.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ehough_finder_exception_AdapterFailureException extends RuntimeException implements ehough_finder_exception_ExceptionInterface
{
    /**
     * @var ehough_finder_adapter_AdapterInterface
     */
    private $adapter;

    /**
     * @param ehough_finder_adapter_AdapterInterface $adapter
     * @param string|null      $message
     * @param Exception|null  $previous
     */
    public function __construct(ehough_finder_adapter_AdapterInterface $adapter, $message = null, Exception $previous = null)
    {
        $this->adapter = $adapter;
        parent::__construct($message ? $message : 'Search failed with "'.$adapter->getName().'" adapter.', $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}
