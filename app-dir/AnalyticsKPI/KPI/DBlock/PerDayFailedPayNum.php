<?php
namespace KPI\DBlock;

class PerDayFailedPayNum extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id,SUM(num_of_fails) FROM fact_payments AS fp
            INNER JOIN dimension_time AS dt ON dt.id=fp.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fp.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Failed Pay.[[Failed payments - Number of failed payments (incorrect input, fraud, etc.)]]");
        $this->setGraphName("Failed payments");
        $this->setGraphVar("fail_pay");
        $this->setGraphLabel("Failed Pay.");
        $this->setTConversion("1");
    }

}

?>
