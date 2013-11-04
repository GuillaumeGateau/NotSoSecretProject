<?php
namespace KPI\DBlock;

class PerDayVisitsFromGG extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id,SUM(num_of_visits_google) FROM fact_site_stats AS fss
            INNER JOIN dimension_time AS dt ON dt.id = fss.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id = fss.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;
        
        $this->setName("#V.GG[[#V.GG - Number of visits coming from Google]]");
        $this->setGraphName("Vis. GG");
        $this->setGraphVar("vis_gg");
        $this->setGraphLabel("#V.GG");
    }

}

?>
