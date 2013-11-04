<?php
namespace KPI\DBlock;

class PerCountryThreeDaysFOSales extends DBlockPerCountry {

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT country_code, SUM(total_revenue) FROM fact_payments AS fp
            INNER JOIN dimension_time AS dt ON dt.id=fp.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fp.dimension_location_id
            WHERE dt.id >= CAST(DATE_SUB(CURDATE(),INTERVAL 4 DAY) AS UNSIGNED) AND
            country_code IN ('".self::$topCountriesList."') AND dimension_subscription_type_id=1
            GROUP BY country_code
        ";

        $this->setName("FO3D");
    }
}

?>
