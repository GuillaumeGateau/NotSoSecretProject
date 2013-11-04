<?php
namespace KPI\DBlock;

class PerSourceRegsFem extends DBlockPerSource{

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT IF(source='(direct)','UNKNOWN',IF(source IS NULL,'UNKNOWN',source)) as src, SUM(num_of_registrations+num_of_registrations_disabled) FROM fact_registrations AS fr
            INNER JOIN dimension_time AS dt ON dt.id=fr.dimension_time_id
            INNER JOIN dimension_traffic_source AS dts ON dts.id=fr.dimension_traffic_source_id
            INNER JOIN dimension_demographics AS dd ON dd.id = fr.dimension_demographics_id
            WHERE dt.id = ".self::$lastDayAvailable." AND 
                dd.gender='FEMALE' AND
                (source IN ('".self::$topSourcesList."') OR source='(direct)' or source IS NULL)
            GROUP BY src;
        ";

        $this->setName("Regs Fem.[[ - Number of registrations by female users]]");
    }

}

?>
