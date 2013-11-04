<?php
namespace KPI\DBlock;

class PerDayWinks extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
        SELECT dt.id, SUM(fua.num_of_winks) 
            FROM fact_users_activity AS fua 
            LEFT JOIN dimension_time AS dt ON dt.id = fua.dimension_time_id 
            LEFT JOIN dimension_location AS dl ON dl.id = fua.dimension_location_id
            LEFT JOIN dimension_demographics AS dd ON dd.id = fua.dimension_demographics_id
                WHERE dt.id > 20110717 AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Winks[[Winks - Total number of winks sent]]");
    }

}

?>