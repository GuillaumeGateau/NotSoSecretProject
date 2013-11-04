<?php
namespace KPI\DBlock;

class LastMonths extends DBlock {

    private $numOfMonths;

    function __construct($numOfMonths = 12) {
        parent::__construct();

        $this->qu1 = "
            select year, LPAD(month,2,'0'), month_name from fact_payments as fp
inner join dimension_time as dt on dt.id = fp.dimension_time_id
group by year, LPAD(month,2,'0'), month, month_name
order by year desc, month desc
limit ".$numOfMonths.";";

        $this->numOfMonths = $numOfMonths;
        $this->setName("Month");
        
    }
    
    function doExecute() {
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();

        foreach($result as $val) {
            $this->values[$val[0].$val[1]] = $val[0].'/'.$val[1];
        }

    }

    function getStartDate() {
        if(!$this->executed) {
            $this->execute();
        }
        return \end(array_keys($this->values))."01";
    }

    function getEndDate() {
        if (!$this->executed) {
            $this->execute();
        }
        return \KPI\DBlock\DBlock::getLastDayAvailable();
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
