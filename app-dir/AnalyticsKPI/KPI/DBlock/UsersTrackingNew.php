<?php
namespace KPI\DBlock;

class UsersTrackingNew extends DBlockPerDay {

    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(num_of_users)
                FROM fact_users_profile AS fup
                LEFT JOIN dimension_time AS dt ON dt.id = fup.dimension_time_id
                LEFT JOIN dimension_location AS dl ON dl.id = fup.dimension_location_id
            WHERE DATE(dimension_registered_time_id) >= DATE_SUB(DATE(dimension_time_id), INTERVAL 3 DAY)
                AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("New Users[[New Users - Users who have registered less than 3 days ago.]]");
    }

}

?>
