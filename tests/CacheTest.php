<?php
namespace blink\redis\tests;

use blink\redis\Cache;
use blink\redis\Client;
use Cache\IntegrationTests\SimpleCacheTest;

/**
 * Class CacheTest
 *
 * @package blink\redis\tests
 */
class CacheTest extends SimpleCacheTest
{
    public function createSimpleCache()
    {
        return new Cache(['redis' => new Client()]);
    }
}