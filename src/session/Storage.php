<?php

namespace blink\redis\session;

use blink\core\BaseObject;
use blink\session\StorageContract;
use blink\redis\SerializerTrait;
use blink\redis\Client;

/**
 * Class SessionStorage
 *
 * @package blink\redis\session
 */
class Storage extends BaseObject implements StorageContract
{
    use SerializerTrait;

    protected $_timeout;

    /**
     * The Redis component used to store sessions.
     *
     * @var string|Client
     */
    public $redis = 'redis';

    public function init()
    {
        if (!$this->redis instanceof Client) {
            $this->redis = make($this->redis);
        }
    }

    public function timeout($timeout)
    {
        $this->_timeout = $timeout;
    }

    public function read($id)
    {
        $value = $this->redis->get($id);

        return $this->unserialize($value);
    }

    public function write($id, array $data)
    {
        $value = $this->serialize($data);

        return (bool)$this->redis->setex($id, $this->_timeout, $value);
    }

    public function destroy($id)
    {
        return (bool)$this->redis->del([$id]);
    }
}
