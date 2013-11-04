<?php
namespace KPI\DBlock;

class PerDayPV extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id,SUM(num_of_page_views) FROM fact_site_stats AS fss
            INNER JOIN dimension_time AS dt ON dt.id=fss.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fss.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;
        
        $this->setName("#PV[[Number of page views - From Google Analytics]]");
        $this->setType("int");
        $this->setGraphName("Page Views");
        $this->setGraphVar("pv_num");
        $this->setGraphLabel("#PV");
    }

}

?>
