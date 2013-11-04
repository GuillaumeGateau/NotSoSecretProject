<?php
namespace KPI\DBlock;

class PerDayAndroidLogins extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
        SELECT dt.id, SUM(fs.num_of_logins) 
            FROM fact_sessions AS fs
            LEFT JOIN dimension_time AS dt ON dt.id = fs.dimension_time_id
            LEFT JOIN dimension_location AS dl ON dl.id = fs.dimension_location_id
            WHERE dimension_app_id = 4 AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Android Logins[[Whenever an user logins into the Android app]]");
    }

}

?>