<?php
namespace KPI\DBlockRT;

class RTNotifiersSent extends DBlockRT {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
        SELECT dt.id, SUM(fns.num_of_actually_sent) 
            FROM fact_notifiers_events AS fns
            LEFT JOIN dimension_time AS dt ON dt.id = fns.dimension_time_id
            WHERE dt.id = CAST(CURDATE() AS UNSIGNED)
            GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Notifiers Sent[[Notifiers Sent - Total number of notifiers sent today]]");
    }

}

?>