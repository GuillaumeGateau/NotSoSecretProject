<?php
namespace KPI\DBlock;

class PerDayV2RGG extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id,ROUND(SUM(num_of_goal1_google+num_of_goal2_google)*100/SUM(num_of_visits_google),2) as v2rgg FROM fact_site_stats AS fss
            INNER JOIN dimension_time AS dt ON dt.id=fss.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fss.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;
        
        $this->setName("%V2R-GG[[%V2R - Percentage rate of conversion from Visit to Registration, for traffic coming from Google]]");
        $this->setGraphName("Vis2Reg GG");
        $this->setGraphVar("v2r_gg");
        $this->setGraphLabel("%V2R-GG");
    }

}

?>
