<?php
namespace KPI\DBlockRT;

abstract class DBlockRTPerSource extends DBlockRT{


    protected $PDO;
    protected $qu1;
    protected $values=array();

    protected static $RTtopSources=null;
    protected static $RTtopSourcesList="";


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters, $limit);

        if(self::$RTtopSources===null || self::$RTtopSourcesList=="") {
            //$this->findTopCountries();
            $db = new RTTopSources(20,self::$lastDayAvailable);
            self::$RTtopSources = $db->execute()->getCol();
            self::$RTtopSourcesList = join('\',\'',  \array_keys(self::$RTtopSources));
        }
        

    }
    
    

    function doExecute() {
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();
        
        $temp = array();
        foreach($result as $key=>$value) {
            $temp[$value[0]] = $value[1];
        }
        
        $sources = self::$RTtopSources;
        foreach($sources as $key=>$value) {
            $this->values[$key] = isset($temp[$key]) ? $temp[$key] : 0;
        }
      
    }


}

?>
