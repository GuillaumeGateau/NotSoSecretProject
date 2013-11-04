<?php
namespace KPI\DBlock;

class PerDayRegsFromFB extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(num_of_registrations+num_of_registrations_disabled) FROM fact_registrations AS f
            INNER JOIN dimension_time AS dt ON dt.id=f.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=f.dimension_location_id
            INNER JOIN dimension_traffic_source AS dts ON dts.id=f.dimension_traffic_source_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate."
                AND source='fbperformads' ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("#Regs From FB[[Number of registrations brought by Facebook]]");
        $this->setType("int");
        $this->setGraphName("#Regs from Facebook");
        $this->setGraphVar("reg_fb");
        $this->setGraphLabel("#Regs from FB");
    }

}

?>
