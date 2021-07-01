<?php
namespace Jankx\MenuIcons;

use Jankx\MenuIcons\Integrations\Megu;
use Jankx\MenuIcons\IntegrationConstract;

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

    private function __construct()
    {
        $this->includes();
        $this->initHooks();
    }

    public function includes()
    {
        require_once JANKX_MENU_ICONS_ROOT . '/menu-icons.php';
    }

    protected function initHooks()
    {
        add_action('after_setup_theme', array($this, 'loadIntegrations'), 20);
    }

    public function loadIntegrations()
    {
        $defaultIntegrations = array(
            'megu' => Megu::class,
        );

        $integrations = apply_filters('jankx_menu_icons_integrations', $defaultIntegrations);
        foreach ($integrations as $name => $cls_integration) {
            if (!class_exists($cls_integration)) {
                continue;
            }

            $integration = new $cls_integration();
            if (!is_a($integration, IntegrationConstract::class)) {
                error_log(sprintf('%s integration is skipped', $cls_integration));
                continue;
            }
            $integration->integrate();
        }
    }
}
