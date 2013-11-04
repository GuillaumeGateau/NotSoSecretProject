<?php
namespace KPI\DBlockRT;

class RTPerSourceSales extends DBlockRTPerSource {

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT IF(source='(direct)','UNKNOWN',IF(source IS NULL,'UNKNOWN',source)) as src, SUM(total_revenue) FROM fact_payments_events AS fp
            INNER JOIN dimension_time AS dt ON dt.id=fp.dimension_time_id
            INNER JOIN dimension_traffic_source AS dts ON dts.id=fp.dimension_traffic_source_id
            WHERE dt.id = CAST(CURDATE() AS UNSIGNED) AND
            (source IN ('".self::$RTtopSourcesList."') OR source='(direct)' or source IS NULL)
            GROUP BY src;
        ";

        $this->setName("€[[Raw sales]]");
    }

}

?>
