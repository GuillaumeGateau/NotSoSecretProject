<?php
namespace KPI\DBlockRT;

class RTRegs extends DBlockRT {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(num_of_registrations) FROM fact_registrations_events AS fre
            INNER JOIN dimension_time AS dt ON dt.id=fre.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fre.dimension_location_id
            WHERE dt.id = CAST(CURDATE() AS UNSIGNED) ".
                        $whereFilters.
                        "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Regs[[Number of registrations - Total number of registrations made today]]");
    }

}

?>