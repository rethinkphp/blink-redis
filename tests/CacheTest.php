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
    use TestCaseTrait {
        setUp as mySetUp;
        tearDown as myTearDown;
    }

    public function setUp()
    {
        self::mySetUp();

        parent::setUp();
    }

    public function createSimpleCache()
    {
        return new Cache(['redis' => new Client()]);
    }

    public function invalidConfigurations()
    {
        return [
            ['none-exists'],
            [new \stdClass()],
        ];
    }

    /**
     * @dataProvider invalidConfigurations
     * @expectedException \blink\core\InvalidConfigException
     */
    public function testInvalidConfigurations($redis)
    {
        new Cache(['redis' => $redis]);
    }

    public function validConfigurations()
    {
        return [
            ['redis'],
            [Client::class],
            [new Client()],
            [
                'class' => Client::class,
                'servers' => 'tcp://127.0.0.1:6379',
            ],
        ];
    }

    /**
     * @dataProvider validConfigurations
     */
    public function testValidConfigurations($redis)
    {
        $cache = new Cache(['redis' => $redis]);

        $this->assertInstanceOf(Client::class, $cache->redis);
    }


    public function tearDown()
    {
        self::myTearDown();
        parent::tearDown();
    }
}
