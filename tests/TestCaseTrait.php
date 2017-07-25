<?php

namespace blink\redis\tests;

use blink\core\Application;
use blink\redis\Client;
use blink\session\Manager;
use blink\redis\session\Storage;

/**
 * Class TestCaseTrait
 *
 * @package blink\redis\tests
 */
trait TestCaseTrait
{
    protected $app;

    public function setUp()
    {
        $this->app = $this->createApplication();
    }

    /**
     * @return \blink\core\Application
     */
    public function createApplication()
    {
        $app = new Application([
            'root' => '.',
            'services' => [
                'redis' => Client::class,
                'session' => [
                    'class' => Manager::class,
                    'storage' => Storage::class,
                ]
            ],
        ]);

        $app->bootstrapIfNeeded();

        return $app;
    }

    public function tearDown()
    {
        $this->app = null;
    }
}
