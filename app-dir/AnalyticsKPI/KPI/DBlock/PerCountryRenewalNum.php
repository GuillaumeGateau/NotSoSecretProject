<?php
namespace KPI\DBlock;

class PerCountryRenewalNum extends DBlockPerCountry {

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT country_code, SUM(num_of_subscriptions) FROM fact_subscriptions AS fs
            INNER JOIN dimension_time AS dt ON dt.id=fs.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fs.dimension_location_id
            INNER JOIN dimension_subscription_type AS dst ON dst.id=fs.dimension_subscription_type_id
            WHERE dt.id = ".self::$lastDayAvailable." 
                AND country_code IN ('".self::$topCountriesList."')
                AND dst.id='2'
            GROUP BY country_code
        ";

        $this->setName("#RN[[Renewals - Number of renewals]]");
    }

}

?>
