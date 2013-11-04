<?php
namespace KPI\DBlockRT;

class RTPerSourceRegs36_50 extends DBlockRTPerSource {

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT IF(source='(direct)','UNKNOWN',IF(source IS NULL,'UNKNOWN',source)) as src, SUM(num_of_registrations) FROM fact_registrations_events AS f
            INNER JOIN dimension_time AS dt ON dt.id=f.dimension_time_id
            INNER JOIN dimension_traffic_source AS dts ON dts.id=f.dimension_traffic_source_id
            INNER JOIN dimension_demographics AS dd ON dd.id = f.dimension_demographics_id
            WHERE dt.id = CAST(CURDATE() AS UNSIGNED) AND (age BETWEEN 36 AND 50) AND
            (source IN ('".self::$RTtopSourcesList."') OR source='(direct)' or source IS NULL)
            GROUP BY src;
        ";

        $this->setName("36-50[[ - Registrations for users age 36-50]]");
    }

}

?>
