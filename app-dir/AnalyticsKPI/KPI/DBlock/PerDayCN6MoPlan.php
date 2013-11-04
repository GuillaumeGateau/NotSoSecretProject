<?php
namespace KPI\DBlock;

class PerDayCN6MoPlan extends DBlockPerDay {

    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            $whereFilters = "";
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id,SUM(num_of_cancellations) FROM fact_terminations AS f
            INNER JOIN dimension_time AS dt ON dt.id=f.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=f.dimension_location_id
            INNER JOIN dimension_plan_type AS dpt ON dpt.id=f.dimension_plan_type_id
            WHERE dpt.id=180
                AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("#CN 6MO[[6-month cancellations - Number of cancellations of 6-month plans]]");
        $this->setGraphName("6MO plans cancellations");
        $this->setGraphVar("cn_6mo");
        $this->setGraphLabel("#CN 6MO");
        $this->setTConversion("1");
    }

}

?>
