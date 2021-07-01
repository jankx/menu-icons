<?php
class Jankx_Menu_Icon_Bootstrap {
    protected function includes() {
        require_once dirname(__FILE__) . '/menu-icons.php';
    }

    public function start() {
        $this->includes();
    }
}

$bootstrap = new Jankx_Menu_Icon_Bootstrap();
$bootstrap->start();

