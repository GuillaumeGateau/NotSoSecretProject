<?php
namespace KPI\base;

class Replica_DBShop {
    
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
            $dsn = "mysql:host=sdeuprod-recover-2.c6oluxwdcmwj.eu-west-1.rds.amazonaws.com;port=3306;dbname=web_app_production";
       		$dbuser = "smartdate";
        	$dbpass = "yosVububhur3";
        
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
