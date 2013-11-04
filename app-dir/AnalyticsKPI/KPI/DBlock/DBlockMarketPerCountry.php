<?php
namespace KPI\DBlock;

abstract class DBlockMarketPerCountry extends DBlockPerDay {

    protected $PDO;
    protected $qu1;
    protected $values=array();

    protected static $MarketTopCountries=null;
    protected static $MarketTopCountriesList="";

    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);


        if(self::$MarketTopCountries===null) {
            //$this->findTopCountries();
            $db = new MarketPerDayCountries();
            self::$MarketTopCountries = $db->execute()->getCol();
            //self::$MarketTopCountriesList = $db->getTopCountriesList();
            self::$MarketTopCountriesList = join('\',\'', \array_keys(self::$MarketTopCountries));
        }
        
    }


    function doExecute() {
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();
        
        $temp = array();
        foreach($result as $key=>$value) {
            $temp[$value[0]] = $value[1];
        }

        $countries = self::$MarketTopCountries;
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
