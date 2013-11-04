<?php
namespace KPI\DBlock;

class PerSourceRNNum extends DBlockPerSource{

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT IF(source='(direct)','UNKNOWN',IF(source IS NULL,'UNKNOWN',source)) as src, SUM(num_of_payments) FROM fact_payments AS fp
            INNER JOIN dimension_time AS dt ON dt.id=fp.dimension_time_id
            INNER JOIN dimension_traffic_source AS dts ON dts.id=fp.dimension_traffic_source_id
            WHERE dt.id = ".self::$lastDayAvailable." AND dimension_subscription_type_id=2 AND
            (source IN ('".self::$topSourcesList."') OR source='(direct)' or source IS NULL)
            GROUP BY src;
        ";

        $this->setName("#RN[[Number of RN - Number of renewals]]");
    }

}

?>
