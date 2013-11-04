<?php
namespace KPI\DBlock;

class PerDayNotifiersSent extends DBlockPerDay {


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
                WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate."
            GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Notifiers Sent[[Notifiers Sent - Total number of notifiers sent]]");
    }

}

?>