<?php
namespace KPI\DBlock;

class MarketPerDayRegsGT25 extends DBlockMarketPerCountry {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);
        
        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT country_code, SUM(num_of_registrations+num_of_registrations_disabled) AS regs FROM fact_registrations AS f
            INNER JOIN dimension_time AS dt ON dt.id=f.dimension_time_id 
            INNER JOIN dimension_location AS dl ON dl.id=f.dimension_location_id 
            INNER JOIN dimension_traffic_source AS dts ON dts.id = dimension_traffic_source_id
            INNER JOIN dimension_demographics AS dd ON dd.id = dimension_demographics_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate."
                AND age >= 25 ".
            $whereFilters.
            "GROUP BY country_code, country HAVING regs>0 
            ORDER BY regs DESC"
        ;

        $this->setName("25+[[[[ - Number of registrations of users age >25]]]]");
    }

    
}

?>
