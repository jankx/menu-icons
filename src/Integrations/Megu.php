<?php
namespace Jankx\MenuIcons\Integrations;

use Menu_Icons_Front_End;
use Jankx\MenuIcons\Integration;

class Megu extends Integration
{
    public function integrate()
    {
        add_action('wp_loaded', array($this, 'createFrontend'));
    }

    public function createFrontend()
    {
        if (class_exists(Menu_Icons_Front_End::class)) {
            add_filter('megamenu_the_title', array(Menu_Icons_Front_End::class, '_add_icon'), 999, 2);
        }
    }
}
