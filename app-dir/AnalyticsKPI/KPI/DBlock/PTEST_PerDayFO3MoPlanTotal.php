<?php
namespace KPI\DBlock;

class PTEST_PerDayFO3MoPlanTotal extends DBlockPerDay {

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
            SELECT dt.id, SUM(num_of_subscriptions) FROM fact_subscriptions AS fs
            LEFT JOIN dimension_time AS dt ON dt.id=fs.dimension_time_id
            LEFT JOIN dimension_location AS dl ON dl.id=fs.dimension_location_id
            LEFT JOIN dimension_subscription_type AS dst ON dst.id=fs.dimension_subscription_type_id
            LEFT JOIN dimension_plan_type AS dpt ON dpt.id=fs.dimension_plan_type_id
            LEFT JOIN dimension_demographics as dd ON dd.id = fs.dimension_demographics_id
            WHERE dst.id=1 AND dpt.id=90 AND dl.id IN (22, 74) AND dd.age >=40
                AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("#3MO TTL[[3-Month plans - Number of 3-Month plans]]");
        $this->setGraphName("3MO plans");
        $this->setGraphVar("fo_3mo");
        $this->setGraphLabel("#3MO TTL");
    }

}

?>
