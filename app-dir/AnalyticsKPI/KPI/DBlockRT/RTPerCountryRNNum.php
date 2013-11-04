<?php
namespace KPI\DBlockRT;

class RTPerCountryRNNum extends DBlockRTPerCountry{

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT country_code, SUM(num_of_subscriptions) FROM fact_subscriptions_events AS fse
            INNER JOIN dimension_time AS dt ON dt.id=fse.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fse.dimension_location_id
            WHERE dt.id = CAST(CURDATE() AS UNSIGNED) AND dimension_subscription_type_id=2 AND
            country_code IN ('".self::$RTtopCountriesList."')
            GROUP BY country_code
        ";

        $this->setName("#RN[[ - Number of Renewals]]");
    }

}

?>
