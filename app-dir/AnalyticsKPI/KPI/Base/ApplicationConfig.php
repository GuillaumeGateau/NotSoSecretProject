<?php
namespace KPI\base;

class ApplicationConfig {

    private static $instance;
    private $values = array();

    private function __construct() {
        $this->init();
    }

    static function instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function init() {
        $this->values['dsn'] = "mysql:host=analytics002.smartdate.com;port=3306;dbname=analytics_production";
        $this->values['dbuser'] = "analytics_ro";
        $this->values['dbpass'] = "dce478655";

        $this->values['baseURI'] = "AnalyticsKPI";
        //$this->values['cachePath'] = "c:\\cache\\";
        //Use below for linux
        //$this->values['cachePath'] = "/Applications/MAMP/htdocs/AnalyticsKPI/cache/";
        $this->values['cachePath'] = "/var/www/app-kpi/current/app-dir/AnalyticsKPI/cache/";
        //$this->values['cachePath'] = "/var/www/app-kpi/cache/";
        $this->values['statPath'] = "/var/www/app-kpi/current/public_html/statex/";
        $this->values['stataInstPath'] = "/usr/local/stata10/";

        $this->values['password'] = "lezerton";
        $this->values['login'] = "admin";
    }

    static function getValue($key) {
        return array_key_exists($key,self::instance()->values) ? self::instance()->values[$key] : null;
    }

}

?>
