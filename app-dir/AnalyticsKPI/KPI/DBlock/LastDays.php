<?php
namespace KPI\DBlock;

class LastDays extends DBlock {

    private $numOfDays;

    function __construct($numOfDays = 10) {
        parent::__construct();

        $this->qu1 = "
            SELECT dimension_time_id FROM fact_payments AS fp
            ORDER BY dimension_time_id DESC LIMIT $numOfDays;
        ";

        $this->numOfDays = $numOfDays;
        $this->setName("Day");
        
    }
    
    function doExecute() {
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetch();

        $lastAvailableDate = $result[0];

        $date = \DateTime::createFromFormat('Ymd', $lastAvailableDate);
        $this->values[$lastAvailableDate] = $date->format('D. Y/m/d');

        $dayInterval = new \DateInterval("P1D");
        for($i=0;$i<$this->numOfDays;$i++) {
            $date->sub($dayInterval);
            $this->values[$date->format('Ymd')] = $date->format('D. Y/m/d');
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
