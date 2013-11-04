<?php
namespace KPI\DBlock;

abstract class DBlockPerCountry extends DBlock{


    protected $PDO;
    protected $qu1;
    protected $values=array();

    protected static $topCountries=null;
    //protected static $topCountriesList="";
    
    //protected static $lastDayAvailable;


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters, $limit);

        if(self::$topCountries===null) {
            //$this->findTopCountries();
            $db = new TopCountries(7,self::$lastDayAvailable);
            self::$topCountries = $db->execute()->getCol();
            //self::$topCountriesList = $db->getTopCountriesList();
        }

    }
    
    function findTopCountries() {
        $query = "
            SELECT country, country_code, SUM(total_revenue) FROM fact_payments AS fp
            INNER JOIN dimension_time AS dt ON dt.id=fp.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fp.dimension_location_id
            WHERE dt.id = ".self::$lastDayAvailable."
            GROUP BY country, country_code HAVING SUM(total_revenue)>0
            ORDER BY SUM(total_revenue) DESC
            LIMIT 10"
        ;

        $stmt = $this->PDO->query($query);
        $result = $stmt->fetchAll();

        $topCountries = array();
        $topCountriesList = array();
        foreach ($result as $key => $value) {
            $topCountries[] = array($value[0],$value[1]);
            $topCountriesList[] = $value[1];
        }

        self::setTopCountries($topCountries);
        self::setTopCountriesList($topCountriesList);
        
    }



    static function setTopCountries(array $topCountries) {
        self::$topCountries = $topCountries;
    }

    static function setTopCountriesList(array $topCountriesList) {
        self::$topCountriesList = join('\',\'', $topCountriesList);
    }
    
    static function getTopCountries() {
        return (self::$topCountries!==null) ? self::$topCountries : array();
    }
    
    static function getTopCountriesList () {
        return (self::$topCountries!==null) ? self::$topCountries : array();
    }

    static function getLastDayAvailable() {
        if(!self::$lastDayAvailable) {
            // Find most recent 7 days with available data
            $db = new LastDays();
            self::$lastDayAvailable = $db->getEndDate();
        }
        return self::$lastDayAvailable;
    }

    function doExecute() {
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();
        
        $temp = array();
        foreach($result as $key=>$value) {
            $temp[$value[0]] = $value[1];
        }

        $countries = self::$topCountries;        
        foreach($countries as $key=>$value) {
            $this->values[$key] = isset($temp[$key]) ? $temp[$key] : 0;
        }
      
    }


    function getCacheStart() {
        return 18000;
    }

    function getCachePeriod() {
        return 86400;
    }

    function cacheIsValid($lastTime) {
        
    }

    function isCachable() {
        return true;
    }
}

?>
