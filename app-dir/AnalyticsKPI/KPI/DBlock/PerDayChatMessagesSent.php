<?php
namespace KPI\DBlock;

class PerDayChatMessagesSent extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
        SELECT dt.id, SUM(fm.num_of_messages_sent) 
            FROM fact_messages_senders AS fm
            LEFT JOIN dimension_time AS dt ON dt.id = fm.dimension_time_id
            LEFT JOIN dimension_demographics AS dd ON dd.id = fm.dimension_demographics_id
            LEFT JOIN dimension_location AS dl ON dl.id = fm.dimension_location_id
            WHERE dimension_app_id = 8 AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Chat Messages[[Chat Messages - Total of chat messages successfully sent]]");
    }

}

?>