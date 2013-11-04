<?php
namespace KPI\DBlock;

abstract class DBlockPerSource extends DBlock{


    protected $PDO;
    protected $qu1;
    protected $values=array();

    protected static $topSources=null;
    protected static $topSourcesList="";


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters, $limit);

        if(self::$topSources===null || self::$topSourcesList=="") {
            //$this->findTopCountries();
            $db = new TopSources(20,self::$lastDayAvailable);
            self::$topSources = $db->execute()->getCol();
            self::$topSourcesList = join('\',\'',  \array_keys(self::$topSources));
        }

    }
    
    

    function doExecute() {
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();
        
        $temp = array();
        foreach($result as $key=>$value) {
            $temp[$value[0]] = $value[1];
        }

        $sources = self::$topSources;
        foreach($sources as $key=>$value) {
            $this->values[$key] = isset($temp[$key]) ? $temp[$key] : 0;
        }
      
    }


    function getCacheStart() {
        return 18000;
    }

    function getCachePeriod() {
        return 86400;
    }

    function isCachable() {
        return true;
    }
}

?>
