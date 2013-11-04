<?php
namespace KPI\Base;

class RequestRegistry extends \KPI\Base\Registry {
    private static $instance;
    private $values = array();

    private function __construct() {}

    static function instance() {
        if(!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function set($key, $value) {
        $this->values[$key] = $value;
    }

    protected function get($key) {
        return isset($this->values[$key]) ? $this->values[$key] : null;
    }

    static function setRequest(\KPI\Controller\Request $request) {
        self::instance()->set('request', $request);
    }

    static function getRequest() {
        return self::instance()->get('request');
    }

}
?>
