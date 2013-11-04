<?php
namespace KPI\DBlock;

class UsersTrackingActive extends DBlockPerDay {

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
            WHERE DATEDIFF(DATE(dimension_time_id), DATE(dimension_registered_time_id)) > 3
                  AND DATEDIFF(DATE(dimension_time_id), DATE(dimension_last_login_time_id)) <= 30
                  AND DATEDIFF(DATE(dimension_time_id), DATE(dimension_last_activity_time_id)) <= 30
                AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Active Users[[Active Users - Users who have logged in over the last 30 days, and have sent messages.
        NOTE: only users who registered more than 3 days ago are considered]]");
    }

}

?>
