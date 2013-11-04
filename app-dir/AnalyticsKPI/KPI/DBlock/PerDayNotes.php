<?php
namespace KPI\DBlock;

class PerDayNotes extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id,SUM(num_of_reports) FROM fact_reports AS f
            INNER JOIN dimension_time AS dt ON dt.id=f.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=f.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;
        
        $this->setName("");
        //$this->setType("int");
        //$this->setGraphName("Num. of Reports");
        //$this->setGraphVar("reports");
        //$this->setGraphLabel("#Reports");
    }

}

?>
