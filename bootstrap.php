<?php
use Jankx\MenuIcons\MenuIcons;

class Jankx_Menu_Icon_Bootstrap {
    public function start() {
        if (!defined('JANKX_MENU_ICONS_ROOT')) {
            define('JANKX_MENU_ICONS_ROOT', dirname(__FILE__));
        }
        MenuIcons::get_instance();
    }
}

$bootstrap = new Jankx_Menu_Icon_Bootstrap();
$bootstrap->start();

