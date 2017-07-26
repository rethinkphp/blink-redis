# A Redis component for the Blink Framework

[![Build Status](https://travis-ci.org/rethinkphp/blink-redis.svg?branch=master)](https://travis-ci.org/rethinkphp/blink-redis)
[![Latest Stable Version](https://poser.pugx.org/blink/redis/v/stable)](https://packagist.org/packages/blink/redis)
[![Latest Unstable Version](https://poser.pugx.org/blink/redis/v/unstable)](https://packagist.org/packages/blink/redis)

## Features

* A Redis Client compatible with [Predis](https://github.com/nrk/predis) API
* Implemented PSR-16 SampleCache
* A Session Storage class to store sessions into redis

## Installation 

You can install the latest version of blink-redis by using Composer:

```
composer require blink/redis:dev-master
```

## Documentation

### Configuring a redis service

You can easily configure a redis service in the services definition file which located to `src/config/services.php` by default. 

The following is a sample example:

```php
'redis' => [
    'class' => blink\redis\Client::class,
    'servers' => ['tcp://127.0.0.1:6379'],
]
```

Once the redis service configured, we can access redis server through `app()->redis` in our application. As 
the Redis component is based on [Predis](https://github.com/nrk/predis), you can refer their documentation on
how to issue command to redis servers.

### Using redis as a cache service

The component provides a PSR-16 SampleCache implementation which using redis as a cache storage. We can define 
a cache service in `services.php` likes the folowing:

```php
'cache' => [
    'class' => blink\redis\cache\SampleCache::class,
    'redis' => 'redis', // The redis service to store cached data
    'prefix' => '',     // The prefix of cached key
]
```

Once the cache service configured, we can access the cache service through `app()->cache` in our application.


### Using redis as session storage

The component also provides a Session Storgae class which allows Blink to store application sessions into redis.
we can configure the session storage in the following way:

```php
'session' => [
    'class' => blink\session\Manager::class,
    'expires' => 3600 * 24 * 15,
    'storage' => [
        'class' => blink\redis\session\Storage::class,
        'redis' => 'redis',  // the redis service to store sessions
    ]
],
```
