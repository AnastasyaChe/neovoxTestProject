<?php

namespace app\traits;

trait SingletonTrait
{
    static $instance = null;

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    protected function __construct()
    {
    }
    protected function __clone()
    {
    }
    protected function __wakeup()
    {
    }
}
