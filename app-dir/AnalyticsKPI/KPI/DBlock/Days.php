<?php
namespace KPI\DBlock;

class Days extends DBlockPerDay {

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            Days between ".self::$startDate." and ".self::$endDate;
        
        $this->setName("Day");
        $this->setASCIIName("Day");
        
    }
    
    function doExecute() {

        $start = \DateTime::createFromFormat('Ymd', self::$startDate);
        $end = \DateTime::createFromFormat('Ymd', self::$endDate);
        $this->values[self::$endDate] = $end->format('D. Y/m/d');
        
        $dayInterval = new \DateInterval("P1D");
        while($end>$start) {
            $end->sub($dayInterval);
            $this->values[$end->format('Ymd')] = $end->format('D. Y/m/d');
        }
        

    }

    function getStartDate() {
        if(!$this->executed) {
            $this->execute();
        }
        return \end(array_keys($this->values));
    }

    function getEndDate() {
        if (!$this->executed) {
            $this->execute();
        }
        return \reset(array_keys($this->values));
    }

    function isCachable() {
        return true;
    }
    
    function getCacheStart() {
        return 18000;
    }

    function getCachePeriod() {
        return 86400;
    }
}

?>
