<?php
namespace KPI\DBlockRT;

class RTFemaleMicroActions extends DBlockRT {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
        SELECT dt.id, SUM(CASE WHEN dd.gender = \"FEMALE\" THEN 1 ELSE 0 END)
                FROM fact_purchases_events AS fpe
                LEFT JOIN dimension_time AS dt ON dt.id = fpe.dimension_time_id
                LEFT JOIN dimension_demographics AS dd ON dd.id = fpe.dimension_demographics_id
                LEFT JOIN dimension_location AS dl ON dl.id = fpe.dimension_location_id
                WHERE dt.id = CAST(CURDATE() AS UNSIGNED) ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Female Micro Actions[[Female Micro Actions - Total number of micro actions made by women]]");
    }

}

?>