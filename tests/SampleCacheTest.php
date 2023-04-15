<?php
namespace blink\redis\tests;

use blink\redis\cache\SampleCache;
use blink\redis\Client;
use Cache\IntegrationTests\SimpleCacheTest as BaseTest;

/**
 * Class CacheTest
 *
 * @package blink\redis\tests
 */
class SampleCacheTest extends BaseTest
{
    use TestCaseTrait {
        setUp as mySetUp;
        tearDown as myTearDown;
    }

    public function setUp(): void
    {
        self::mySetUp();

        parent::setUp();
    }

    public function createSimpleCache()
    {
        return new SampleCache(['redis' => new Client()]);
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
     */
    public function testInvalidConfigurations($redis)
    {
        $this->expectException(\blink\core\InvalidConfigException::class);

        new SampleCache(['redis' => $redis]);
    }

    public function validConfigurations()
    {
        return [
            ['redis'],
            [Client::class],
            [new Client()],
            [
                [
                    'class' => Client::class,
                    'servers' => 'tcp://127.0.0.1:6379',
                ],
            ],
        ];
    }

    /**
     * @dataProvider validConfigurations
     */
    public function testValidConfigurations($redis)
    {
        $cache = new SampleCache(['redis' => $redis]);

        $this->assertInstanceOf(Client::class, $cache->redis);
    }


    public function tearDown(): void
    {
        self::myTearDown();
        parent::tearDown();
    }
}
