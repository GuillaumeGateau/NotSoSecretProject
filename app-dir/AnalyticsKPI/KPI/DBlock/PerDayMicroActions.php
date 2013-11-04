<?php
namespace KPI\DBlock;

class PerDayMicroActions extends DBlockPerDay{


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
        SELECT dt.id, SUM(fp.num_of_purchases)
        FROM fact_purchases AS fp
            LEFT JOIN dimension_time AS dt ON dt.id = fp.dimension_time_id
            LEFT JOIN dimension_location AS dl ON dl.id = fp.dimension_location_id
        WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Micro Actions[[Micro Actions - Number of times users pay with credits]]");
        
    }

}

?>