<?php
namespace KPI\DBlock;

class PerCountryThreeDaysRegs extends DBlockPerCountry {
    
    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT country_code, SUM(num_of_registrations+num_of_registrations_disabled) FROM fact_registrations AS fr
            INNER JOIN dimension_time AS dt ON dt.id=fr.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fr.dimension_location_id
            WHERE dt.id >= CAST(DATE_SUB(CURDATE(),INTERVAL 4 DAY) AS UNSIGNED) AND
            country_code IN ('".self::$topCountriesList."')
            GROUP BY country_code
        ";

        $this->setName("R3D");
    }

}

?>
