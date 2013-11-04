<?php
namespace KPI\DBlock;

class PerSourceFONum extends DBlockPerSource{

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT IF(source='(direct)','UNKNOWN',IF(source IS NULL,'UNKNOWN',source)) as src, SUM(num_of_payments) FROM fact_payments AS fp
            INNER JOIN dimension_time AS dt ON dt.id=fp.dimension_time_id
            INNER JOIN dimension_traffic_source AS dts ON dts.id=fp.dimension_traffic_source_id
            WHERE dt.id = ".self::$lastDayAvailable." AND dimension_subscription_type_id=1 AND
            (source IN ('".self::$topSourcesList."') OR source='(direct)' or source IS NULL)
            GROUP BY src;
        ";

        $this->setName("#FO[[Number of FO - Number of First Orders]]");
    }

}

?>
