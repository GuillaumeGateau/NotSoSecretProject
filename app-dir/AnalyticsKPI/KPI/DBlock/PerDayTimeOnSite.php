<?php
namespace KPI\DBlock;

class PerDayTimeOnSite extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id,ROUND(SUM(total_time_on_site_secs)/60,2) FROM fact_site_stats AS fss
            INNER JOIN dimension_time AS dt ON dt.id=fss.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fss.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;
        
        $this->setName("Time on site[[ - Time spent on the site in minutes]]");
        $this->setGraphName("Time on site (min)");
        $this->setGraphVar("vis_tim");
        $this->setGraphLabel("Time spent");
    }

}

?>
