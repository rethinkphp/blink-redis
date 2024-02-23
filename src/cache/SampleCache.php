<?php

namespace blink\redis\cache;

use blink\core\BaseObject;
use blink\di\attributes\Inject;
use blink\redis\SerializerTrait;
use DateTime;
use DateInterval;
use Traversable;
use blink\redis\Client;
use Psr\SimpleCache\CacheInterface;
use blink\core\InvalidConfigException;

/**
 * Class SampleCache
 *
 * @package blink\redis
 */
class SampleCache extends BaseObject implements CacheInterface
{
    use SerializerTrait;

    /**
     * The redis client
     *
     * @var \blink\redis\Client
     */
    public Client $redis;

    /**
     * The cache key prefix
     *
     * @var string
     */
    public string $prefix;

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        $this->assertKey($key);

        $key = $this->buildKey($key);

        $value = $this->redis->get($key);

        return $this->unserialize($value, $default);
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value, $ttl = null)
    {
        $this->assertKey($key);

        return $this->setInternal($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function delete($key)
    {
        $this->assertKey($key);

        $key = $this->buildKey($key);

        $this->redis->del([$key]);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        $this->redis->flushall();

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getMultiple($keys, $default = null)
    {
        if (!($keys instanceof Traversable || is_array($keys))) {
            throw new InvalidArgumentException('The $keys parameters must be an array or Traversable instance');
        }

        $keys = $this->normalizeKeys($keys);

        array_walk($keys, [$this, 'assertKey']);

        $storedKeys = array_map([$this, 'buildKey'], $keys);

        $values = $this->redis->mget($storedKeys);

        $values = array_combine($keys, $values);

        foreach ($values as $key => $value) {
            $values[$key] = $this->unserialize($value, $default);
        }

        return $values;
    }

    /**
     * @inheritDoc
     */
    public function setMultiple($values, $ttl = null)
    {
        if (!($values instanceof Traversable || is_array($values))) {
            throw new InvalidArgumentException('The $values parameters must be an array or Traversable instance');
        }

        $values = $this->normalizeValues($values);

        $keys = array_column($values, 0);

        array_walk($keys, [$this, 'assertKey']);

        foreach ($values as list($key, $value)) {
            $this->setInternal((string)$key, $value, $ttl);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple($keys)
    {
        if (!($keys instanceof Traversable || is_array($keys))) {
            throw new InvalidArgumentException('The $keys parameters must be an array or Traversable instance');
        }

        if ($keys === []) {
            return true;
        }

        $keys = $this->normalizeKeys($keys);

        array_walk($keys, [$this, 'assertKey']);

        $keys = array_map([$this, 'buildKey'], $keys);

        $this->redis->del($keys);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        $this->assertKey($key);

        return (bool)$this->redis->exists($this->buildKey($key));
    }

    protected function buildKey($key)
    {
        return $this->prefix . $key;
    }

    protected function setInternal($key, $value, $ttl)
    {
        $ttl = $this->normalizeTtl($ttl);

        $key = $this->buildKey($key);
        $value = $this->serialize($value);

        if ($ttl === null) {
            $this->redis->set($key, $value);
        } else if ($ttl > 0) {
            $this->redis->setex($key, $ttl, $value);
        } else {
            $this->redis->del([$key]);
        }

        return true;
    }

    protected function normalizeTtl($ttl)
    {
        if ($ttl instanceof DateInterval) {
            return (new DateTime())->add($ttl)->getTimestamp() - time();
        } else if ($ttl instanceof DateTime) {
            return $ttl->getTimestamp() - time();
        } else if (is_int($ttl) || $ttl === null) {
            return $ttl;
        } else {
            throw new InvalidArgumentException('The $ttl parameter is invalid');
        }
    }

    protected function assertKey($key)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException('Cache key must be strings');
        } else if ($key === '') {
            throw new InvalidArgumentException('Cache key can not be empty string');
        } else if (preg_match('#[{}()@:/\\\\]#', $key)) {
            throw new InvalidArgumentException("Cache key can not contains '{}()@:/\\' characters");
        }
    }

    /**
     * @param Traversable $values
     * @return array
     */
    protected function normalizeValues($values)
    {
        $normalized = [];
        $isArray = is_array($values);

        foreach ($values as $key => $value) {
            if ($isArray) {
                $key = (string)$key;
            }

            $normalized[] = [$key, $value];
        }

        return $normalized;
    }

    /**
     * @param Traversable $keys
     * @return array
     */
    protected function normalizeKeys($keys)
    {
        if (is_array($keys)) {
            return $keys;
        }

        $results = [];

        foreach ($keys as $key) {
            $results[] = $key;
        }

        return $results;
    }
}
