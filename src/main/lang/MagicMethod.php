<?php

declare(strict_types=1);

namespace vinyl\std\lang;

/**
 * Class MagicMethod
 *
 * Class that contains constants with php magic method names
 */
final class MagicMethod
{
    public const CONSTRUCT   = ' __construct';
    public const DESTRUCT    = ' __destruct';
    public const CALL        = ' __call';
    public const CALL_STATIC = ' __callStatic';
    public const GET         = ' __get';
    public const SET         = ' __set';
    public const ISSET       = ' __isset';
    public const UNSET       = ' __unset';
    public const WAKEUP      = ' __wakeup';
    public const SERIALIZE   = ' __serialize';
    public const UNSERIALIZE = ' __unserialize';
    public const TO_STRING   = ' __toString';
    public const INVOKE      = ' __invoke';
    public const SET_STATE   = ' __set_state';
    public const CLONE       = ' __clone';
    public const DEBUG_INFO  = ' __debugInfo';
}
