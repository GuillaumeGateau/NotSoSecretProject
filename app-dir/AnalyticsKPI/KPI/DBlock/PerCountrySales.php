<?php
namespace KPI\DBlock;

class PerCountrySales extends DBlockPerCountry{

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT country_code, SUM(total_revenue) FROM fact_payments AS fp
            INNER JOIN dimension_time AS dt ON dt.id=fp.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fp.dimension_location_id
            WHERE dt.id = ".self::$lastDayAvailable." AND
            country_code IN ('".self::$topCountriesList."')
            GROUP BY country_code;
        ";

        $this->setName("€[[Raw sales - Total revenue fdrom successful payments (currently without credits purchase and micropayments)]]");
    }

}

?>
