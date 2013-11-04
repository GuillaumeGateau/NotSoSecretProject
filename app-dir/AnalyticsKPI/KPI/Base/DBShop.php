<?php
namespace KPI\base;

class DBShop {
    
    private static $instance;
    
    private $PDO;

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
        if (!isset($this->PDO)) {
            $dsn = \KPI\Base\ApplicationConfig::instance()->getValue('dsn');
            $dbuser = \KPI\Base\ApplicationConfig::instance()->getValue('dbuser');
            $dbpass = \KPI\Base\ApplicationConfig::instance()->getValue('dbpass');
            if (is_null($dsn) || is_null($dbuser) || is_null($dbpass)) {
                throw new \Exception("Database configuration missing!");
            }
            $this->PDO = new \PDO($dsn, $dbuser, $dbpass);
            $this->PDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
          
    }

    function getPDO() {
        return $this->PDO;
    }

}

?>
