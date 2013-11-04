<?php
namespace KPI\DBlockRT;

class RTPerCountryRegs25_35 extends DBlockRTPerCountry{

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT country_code, SUM(num_of_registrations) FROM fact_registrations_events AS fre
            INNER JOIN dimension_time AS dt ON dt.id=fre.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fre.dimension_location_id
            INNER JOIN dimension_demographics AS dd ON dd.id = fre.dimension_demographics_id
            WHERE dt.id = CAST(CURDATE() AS UNSIGNED) AND (age BETWEEN 25 AND 35) AND
            country_code IN ('".self::$RTtopCountriesList."')
            GROUP BY country_code
        ";

        $this->setName("25-35[[ - Registrations for users age 25-35]]");
    }

}

?>
