<?php
namespace Jankx\MenuIcons;

class MenuIcons
{
    protected static $instance;

    public static function get_instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __contruct()
    {
    }
}
