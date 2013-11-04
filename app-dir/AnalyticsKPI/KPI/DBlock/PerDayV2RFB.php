<?php
namespace KPI\DBlock;

class PerDayV2RFB extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id,ROUND(SUM(num_of_goal1_facebook+num_of_goal2_facebook)*100/SUM(num_of_visits_facebook),2) as v2rfb FROM fact_site_stats AS fss
            INNER JOIN dimension_time AS dt ON dt.id=fss.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fss.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;
        
        $this->setName("%V2R-FB[[%V2R - Percentage rate of conversion from Visit to Registration, for traffic coming from Facebook]]");
        $this->setGraphName("Vis2Reg FB");
        $this->setGraphVar("v2r_fb");
        $this->setGraphLabel("%V2R-FB");
    }

}

?>
