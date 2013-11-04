<?php
namespace KPI\DBlock;

class PerDaySales extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(total_revenue) FROM fact_payments AS fp
            INNER JOIN dimension_time AS dt ON dt.id = fp.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id = fp.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;
        

        $this->setName("€[[Raw Sales - All revenue (currently does not include micropayments and purchases of credits)]]");
        $this->setType("float");
        $this->setASCIIName("Sales");
        $this->setGraphName("Sales");
        $this->setGraphVar("sales");
        $this->setGraphLabel("€Sales");
    }

}

?>
