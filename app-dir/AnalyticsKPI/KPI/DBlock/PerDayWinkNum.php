<?php
namespace KPI\DBlock;

class PerDayWinkNum extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id,SUM(num_of_winks) FROM fact_users_activity AS fua
            INNER JOIN dimension_time AS dt ON dt.id=fua.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fua.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;
        
        $this->setName("#Winks[[ - Total! number of winks]]");
        $this->setType("int");
        $this->setGraphName("Num. winks");
        $this->setGraphVar("winks");
        $this->setGraphLabel("#Winks");
    }

}

?>
