<?php
namespace KPI\DBlock;

class PerCountryFOPromoRate extends DBlockPerCountry {

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT country_code, ROUND(SUM(num_of_subscriptions_with_promo)/SUM(num_of_subscriptions)*100,2) AS promo_rate FROM fact_subscriptions AS fs
            INNER JOIN dimension_time AS dt ON dt.id=fs.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fs.dimension_location_id
            INNER JOIN dimension_subscription_type AS dst ON dst.id=fs.dimension_subscription_type_id
            WHERE dt.id = ".self::$lastDayAvailable." 
                AND country_code IN ('".self::$topCountriesList."')
            GROUP BY country_code
        ";

        $this->setName("%PROMO[[Promo rate - Percentage of First Orders and Renewals with a promo code]]");
    }


}

?>
