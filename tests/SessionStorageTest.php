<?php

namespace blink\redis\tests;

use blink\session\Manager;
use blink\session\Session;
use blink\testing\TestCase;

/**
 * Class SessionStorageTest
 *
 * @package blink\redis\tests
 */
class SessionStorageTest extends TestCase
{
    use TestCaseTrait;

    public function testSimple()
    {
        /** @var Manager $manager */
        $manager = session();

        $session = $manager->put(['foo' => 'bar']);

        $this->assertEquals(32, strlen($session->id));

        $bag = $manager->get($session->id);
        $this->assertInstanceOf(Session::class, $bag);

        $bag->set('foo', 'bar');
        $this->assertTrue($manager->set($session->id, $bag));

        $this->assertTrue($manager->destroy($session->id));
        $this->assertNull($manager->get($session->id));
    }
}
