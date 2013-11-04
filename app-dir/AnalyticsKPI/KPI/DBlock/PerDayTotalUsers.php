<?php
namespace KPI\DBlock;

class PerDayTotalUsers extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(num_of_users) FROM fact_users_profile AS fup
            INNER JOIN dimension_time AS dt ON dt.id = fup.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id = fup.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Total users[[ - Total number of users]]");
        $this->setType("int");
        $this->setGraphName("Num. of users");
        $this->setGraphVar("user_num");
        $this->setGraphLabel("Total users");
    }

}

?>
