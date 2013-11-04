<?php
namespace KPI\DBlock;

class PerDayFO1MoPlan extends DBlockPerDay {


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
            SELECT dt.id,SUM(num_of_subscriptions) FROM fact_subscriptions AS fs
            INNER JOIN dimension_time AS dt ON dt.id=fs.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fs.dimension_location_id
            INNER JOIN dimension_subscription_type AS dst ON dst.id=fs.dimension_subscription_type_id
            INNER JOIN dimension_plan_type AS dpt ON dpt.id=fs.dimension_plan_type_id
            WHERE dst.id=1 AND dpt.id=30
                AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("#1MO[[1-Month plans - Number of 1-Month plans]]");
        $this->setGraphName("1MO plans");
        $this->setGraphVar("fo_1mo");
        $this->setGraphLabel("#1MO");
    }

}

?>
