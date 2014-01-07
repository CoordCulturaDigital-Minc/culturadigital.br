<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Thomas Tourlourat <thomas@tourlourat.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Logs to a Redis key using rpush
 *
 * usage example:
 *
 *   $log = new Logger('application');
 *   $redis = new RedisHandler(new Predis\Client("tcp://localhost:6379"), "logs", "prod");
 *   $log->pushHandler($redis);
 *
 * @author Thomas Tourlourat <thomas@tourlourat.com>
 */
class ehough_epilog_handler_RedisHandler extends ehough_epilog_handler_AbstractProcessingHandler
{
    private $redisClient;
    private $redisKey;

    # redis instance, key to use
    public function __construct($redis, $key, $level = ehough_epilog_Logger::DEBUG, $bubble = true)
    {
        if (!(($redis instanceof \Predis\Client) || ($redis instanceof Redis))) {
            throw new InvalidArgumentException('Predis\Client or Redis instance required');
        }

        $this->redisClient = $redis;
        $this->redisKey = $key;

        parent::__construct($level, $bubble);
    }

    protected function write(array $record)
    {
        $this->redisClient->rpush($this->redisKey, $record["formatted"]);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new ehough_epilog_formatter_LineFormatter();
    }
}
