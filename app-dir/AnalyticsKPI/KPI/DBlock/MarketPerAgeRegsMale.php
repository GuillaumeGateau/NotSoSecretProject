<?php
namespace KPI\DBlock;

class MarketPerAgeRegsMale extends DBlockMarketPerAge {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);
        
        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT
                CASE
                    WHEN age<25 THEN '18-24'
                    WHEN age BETWEEN 25 AND 35 THEN '25-35'
                    WHEN age BETWEEN 36 AND 50 THEN '36-50'
                    WHEN age>50 THEN '51+'
                END AS Age
            , SUM(num_of_registrations+num_of_registrations_disabled) AS regs FROM fact_registrations AS f
            INNER JOIN dimension_time AS dt ON dt.id=f.dimension_time_id 
            INNER JOIN dimension_traffic_source AS dts ON dts.id = dimension_traffic_source_id
            INNER JOIN dimension_demographics AS dd ON dd.id = dimension_demographics_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate."
                AND gender = 'MALE' ".
            $whereFilters.
            "GROUP BY Age"
        ;

        $this->setName("Male[[Male regs. - Number of male registrations]]");
    }

    
}

?>
