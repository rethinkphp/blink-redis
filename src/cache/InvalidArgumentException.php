<?php

namespace blink\redis\cache;

use InvalidArgumentException as BaseException;

/**
 * Class InvalidArgumentException
 *
 * @package blink\redis
 */
class InvalidArgumentException extends BaseException implements \Psr\SimpleCache\InvalidArgumentException
{

}
