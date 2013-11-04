<?php
namespace KPI\DBlock;

abstract class DBlockMarketPerAge extends DBlockPerDay {

    protected $PDO;
    protected $qu1;
    protected $values=array();

    protected static $ageList;

    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        self::$ageList = array("18-24","25-35","36-50","51+");
        
    }


    function doExecute() {
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();
        
        $temp = array();
        foreach($result as $key=>$value) {
            $temp[$value[0]] = $value[1];
        }

        $ageList = self::$ageList;
        foreach($ageList as $key) {
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
