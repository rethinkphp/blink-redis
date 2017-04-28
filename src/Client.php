<?php

namespace blink\redis;

use blink\core\Configurable;
use blink\core\ObjectTrait;
use Predis\Client as BaseClient;

/**
 * Class Client
 *
 * @package blink\redis
 */
class Client extends BaseClient implements Configurable
{
    use ObjectTrait {
        __construct as __traitConstruct;
    }

    public $servers;

    public $profile;
    public $prefix;
    public $exceptions;
    public $connections;
    public $cluster;
    public $replication;
    public $aggregate;

    public function __construct($config = [])
    {
        self::__traitConstruct($config);

        parent::__construct($this->servers, $this->resolveOptions());
    }

    protected function resolveOptions()
    {
        $optionNames = ['profile', 'prefix', 'exceptions', 'connections', 'cluster', 'replication', 'aggregate'];
        $options = [];

        foreach ($optionNames as $name) {
            if ($this->$name !== null) {
                $options[$name] = $this->$name;
            }
        }

        return $options;
    }

    public function __call($commandID, $arguments)
    {
        return parent::__call($commandID, $arguments);
    }
}
