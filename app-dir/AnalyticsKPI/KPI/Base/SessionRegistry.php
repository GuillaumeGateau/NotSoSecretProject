<?php
namespace KPI\Base;

class SessionRegistry extends Registry {
    private static $instance;

    private function __construct() {
        \session_start();
    }

    static function instance() {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function set($key,$value) {
        $_SESSION[__CLASS__][$key] = $value;
    }

    protected function get($key){
        return isset($_SESSION[__CLASS__][$key]) ? $_SESSION[__CLASS__][$key] : null;
    }

    static function setSession($key,$value){
        self::instance()->set($key,$value);
    }

    static function getSession($key){
        return self::instance()->get($key);
    }
}
?>
