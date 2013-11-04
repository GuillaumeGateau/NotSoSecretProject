<?php
namespace KPI\DBlockRT;

class RTWinks extends DBlockRT {


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
            LEFT JOIN dimension_time AS dt
            ON dt.id = fua.dimension_time_id
            LEFT JOIN dimension_demographics AS dd
            ON dd.id = fua.dimension_demographics_id
                WHERE dt.id = CAST(CURDATE() AS UNSIGNED) ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Winks[[Winks - Total number of winks sent]]");
    }

}

?>