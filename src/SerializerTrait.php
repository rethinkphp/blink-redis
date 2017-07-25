<?php

namespace blink\redis;

/**
 * Class SerializerTrait
 *
 * @package blink\redis
 */
trait SerializerTrait
{
    /**
     * The serializer
     *
     * @var array
     */
    public $serializer = ['serialize', 'unserialize'];

    protected function serialize($value)
    {
        return $this->serializer[0]($value);
    }

    protected function unserialize($value, $default = null)
    {
        if ($value) {
            return $this->serializer[1]($value);
        } else {
            return $default;
        }
    }
}
