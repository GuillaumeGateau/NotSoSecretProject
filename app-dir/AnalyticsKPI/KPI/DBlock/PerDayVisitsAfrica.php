<?php
namespace KPI\DBlock;

class PerDayVisitsAfrica extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id,SUM(num_of_visits) FROM fact_site_stats AS fss
            INNER JOIN dimension_time AS dt ON dt.id=fss.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fss.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate."
                AND continent='Africa' ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;
        
        $this->setName("#V.Africa[[ - Number of visits coming from an African country]]");
        $this->setType("int");
        $this->setGraphName("VisAfrica");
        $this->setGraphVar("vis_afr");
        $this->setGraphLabel("#V.Africa");
        $this->setTConversion("1");
    }

}

?>
