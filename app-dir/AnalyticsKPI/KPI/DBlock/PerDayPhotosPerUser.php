<?php
namespace KPI\DBlock;

class PerDayPhotosPerUser extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, AVG(avg_photos_per_user_approved) FROM fact_users_profile AS fup
            INNER JOIN dimension_time AS dt ON dt.id=fup.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fup.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Avg.Pic/user");
        $this->setType("int");
    }

}

?>
