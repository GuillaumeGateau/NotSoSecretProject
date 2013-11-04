<?php
namespace KPI\DBlock;

class PerDayChatUniqueRecipients extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        // num_of_unique_recipients does not work accross dimensions
        $this->qu1 = "
        SELECT dt.id, SUM(fm.num_of_unique_recipients) 
            FROM fact_messages_recipients AS fm
            LEFT JOIN dimension_time AS dt ON dt.id = fm.dimension_time_id
            LEFT JOIN dimension_demographics AS dd ON dd.id = fm.dimension_demographics_id
            LEFT JOIN dimension_location AS dl ON dl.id = fm.dimension_location_id
            WHERE dimension_app_id = 8 AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Chat Unique Recipients[[Chat Unique Recipients - Unique users receiving messages]]");
    }

}

?>