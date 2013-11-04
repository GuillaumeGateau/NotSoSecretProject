<?php
namespace KPI\DBlock;

class PerCountryChargebacks extends DBlockPerCountry {

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT country_code, ROUND(SUM(total_chargebacks),2) FROM fact_payments AS fp
            INNER JOIN dimension_time AS dt ON dt.id=fp.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fp.dimension_location_id
            WHERE dt.id = ".self::$lastDayAvailable." AND
            country_code IN ('".self::$topCountriesList."')
            GROUP BY country_code
        ";

        $this->setName("€ Chargebacks[[ - Amount in new chargebacks]]");
    }

}

?>
