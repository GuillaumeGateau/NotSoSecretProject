<?php
namespace KPI\DBlockRT;

class RTPerCountrySales extends DBlockRTPerCountry{

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT country_code, SUM(total_revenue) FROM fact_payments_events AS fpe
            INNER JOIN dimension_time AS dt ON dt.id=fpe.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fpe.dimension_location_id
            WHERE dt.id = CAST(CURDATE() AS UNSIGNED) AND
            country_code IN ('".self::$RTtopCountriesList."')
            GROUP BY country_code
        ";

        $this->setName("€[[Raw Sales]]");
    }

}

?>
