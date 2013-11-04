<?php
namespace KPI\DBlock;

abstract class DBlockMarketPerDay extends DBlockPerDay {

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

        $days = self::$days;
        foreach($days as $day) {
            $this->values[$day] = isset($temp[$day]) ? $temp[$day] : 0;
        }
        
        
    }

   
}

?>
