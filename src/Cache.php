<?php

namespace blink\redis;

use blink\core\Object;
use blink\di\Container;
use Psr\SimpleCache\CacheInterface;

/**
 * Class Cache
 *
 * @package blink\redis
 */
class Cache extends Object implements CacheInterface
{
    /**
     * The redis client
     *
     * @var \blink\redis\Client
     */
    public $redis;

    /**
     * The cache key prefix
     *
     * @var string
     */
    public $prefix;

    public function __construct(Container $container, $config = [])
    {

        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        $key = $this->buildKey($key);

        $value = $this->redis->get($key);

    }

    /**
     * @inheritDoc
     */
    public function set($key, $value, $ttl = null)
    {
        // TODO: Implement set() method.
    }

    /**
     * @inheritDoc
     */
    public function delete($key)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        // TODO: Implement clear() method.
    }

    /**
     * @inheritDoc
     */
    public function getMultiple($keys, $default = null)
    {
        // TODO: Implement getMultiple() method.
    }

    /**
     * @inheritDoc
     */
    public function setMultiple($values, $ttl = null)
    {
        // TODO: Implement setMultiple() method.
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple($keys)
    {
        $keys = array_map([$this, 'buildKey'], $keys);

        return (bool)$this->redis->del($keys);
    }

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        return $this->redis->exists($this->buildKey($key));
    }

    protected function buildKey($key)
    {
        return $this->prefix . $key;
    }
}
