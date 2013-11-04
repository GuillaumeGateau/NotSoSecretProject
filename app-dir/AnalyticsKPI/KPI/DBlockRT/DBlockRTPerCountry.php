<?php
namespace KPI\DBlockRT;

abstract class DBlockRTPerCountry extends DBlock{

    protected $PDO;
    protected $qu1;
    protected $values=array();

    protected static $RTtopCountries=null;
    protected static $RTtopCountriesList="";
    
    protected static $lastDayAvailable;


    function __construct() {
        parent::__construct();


        if(self::$RTtopCountries===null) {
            //$this->findTopCountries();
            $db = new RTTopCountries(10);
            self::$RTtopCountries = $db->execute()->getCol();
            //self::$RTtopCountriesList = $db->getTopCountriesList();
            self::$RTtopCountriesList = join('\',\'', \array_keys(self::$RTtopCountries));
        }
    }


    function doExecute() {
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();
        
        $temp = array();
        foreach($result as $key=>$value) {
            $temp[$value[0]] = $value[1];
        }

        $countries = self::$RTtopCountries;
        foreach($countries as $key=>$value) {
            $this->values[$key] = isset($temp[$key]) ? $temp[$key] : 0;
        }
      
    }

    function isCachable() {
        return true;
    }

    function getCacheStart() {
        return null;
    }

    function getCachePeriod() {
        return 300;
    }
}

?>
