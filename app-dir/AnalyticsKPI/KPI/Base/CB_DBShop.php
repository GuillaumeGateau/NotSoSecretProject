<?php
namespace KPI\base;

class CB_DBShop {
    
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
            $dsn = "mysql:host=analytics002.smartdate.com;port=3306;dbname=analytics_kpi_production";
       		$dbuser = "analytics_ro";
        	$dbpass = "dce478655";
        	
        	//$dsn = "mysql:host=localhost;port=8889;dbname=analytics_kpi_production";
       		//$dbuser = "root";
        	//$dbpass = "root";
        
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
