<?php
namespace KPI\DBlock;

class UsersTrackingActiveDaily extends DBlockPerDay {

    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(num_of_active_users)
                FROM fact_users_activity AS fup
                LEFT JOIN dimension_time AS dt ON dt.id = fup.dimension_time_id
                LEFT JOIN dimension_location AS dl ON dl.id = fup.dimension_location_id
            WHERE DATEDIFF(DATE(dimension_time_id), DATE(dimension_registered_time_id)) > 3
                AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Daily Active Users[[Daily Active Users - Users who have sent messages.
        NOTE: only users who registered more than 3 days ago are considered]]");
    }

}

?>
