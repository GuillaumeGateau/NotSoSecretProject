<?php
namespace KPI\DBlock\Credits;

class PerDayCreditPlansHigh extends \KPI\DBlock\DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, ROUND(SUM(if(dimension_credit_plan_id = 600, num_of_purchases, 0)) / SUM(num_of_purchases) * 100, 2)
            FROM fact_credits AS fc
            LEFT JOIN dimension_time AS dt ON dt.id = fc.dimension_time_id
            LEFT JOIN dimension_location AS dl ON dl.id = fc.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("% High Plan[[% High Plan - % of credit subscriptions using the highest plan]]");
    }

}

?>