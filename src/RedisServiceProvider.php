<?php

declare(strict_types=1);

namespace blink\redis;

use blink\di\Container;
use blink\di\ServiceProvider;
use blink\redis\cache\SampleCache;
use Predis\Connection\Parameters;

class RedisServiceProvider extends ServiceProvider
{
    public function register(Container $container): void
    {
        $redisUrl = env('redis_url', 'tcp://127.0.0.1:6379');

        $container->bind('redis', [
            'class' => Client::class,
            'servers' => Parameters::parse($redisUrl),
        ]);

        $container->bind('cache', function () use ($container) {
            return new SampleCache([
                'redis' => $container->get('redis'),
                'prefix' => env('cache_prefix', 'cache_'),
            ]);
        });
    }
}
