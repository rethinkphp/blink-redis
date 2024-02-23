<?php

declare(strict_types=1);

namespace blink\redis;

use blink\di\Container;
use blink\di\ServiceProvider;
use blink\redis\cache\SampleCache;

class RedisServiceProvider extends ServiceProvider
{
    public function register(Container $container): void
    {
        $container->bind('redis', [
            'class' => Client::class,
            'servers' => [env('redis_url', 'tcp://127.0.0.1:6379')],
        ]);

        $container->bind('cache', function () use ($container) {
            return new SampleCache([
                'redis' => $container->get('redis'),
                'prefix' => env('cache_prefix_', 'cache_'),
            ]);
        });
    }
}
