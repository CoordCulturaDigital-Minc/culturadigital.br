<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//namespace Symfony\Component\EventDispatcher;

/**
 * A read-only proxy for an event dispatcher.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ehough_tickertape_ImmutableEventDispatcher implements ehough_tickertape_EventDispatcherInterface
{
    /**
     * The proxied dispatcher.
     * @var ehough_tickertape_EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Creates an unmodifiable proxy for an event dispatcher.
     *
     * @param ehough_tickertape_EventDispatcherInterface $dispatcher The proxied event dispatcher.
     */
    public function __construct(ehough_tickertape_EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($eventName, ehough_tickertape_Event $event = null)
    {
        return $this->dispatcher->dispatch($eventName, $event);
    }

    /**
     * {@inheritdoc}
     */
    public function addListener($eventName, $listener, $priority = 0)
    {
        throw new BadMethodCallException('Unmodifiable event dispatchers must not be modified.');
    }

    /**
     * {@inheritdoc}
     */
    public function addSubscriber(ehough_tickertape_EventSubscriberInterface $subscriber)
    {
        throw new BadMethodCallException('Unmodifiable event dispatchers must not be modified.');
    }

    /**
     * {@inheritdoc}
     */
    public function removeListener($eventName, $listener)
    {
        throw new BadMethodCallException('Unmodifiable event dispatchers must not be modified.');
    }

    /**
     * {@inheritdoc}
     */
    public function removeSubscriber(ehough_tickertape_EventSubscriberInterface $subscriber)
    {
        throw new BadMethodCallException('Unmodifiable event dispatchers must not be modified.');
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners($eventName = null)
    {
        return $this->dispatcher->getListeners($eventName);
    }

    /**
     * {@inheritdoc}
     */
    public function hasListeners($eventName = null)
    {
        return $this->dispatcher->hasListeners($eventName);
    }
}
