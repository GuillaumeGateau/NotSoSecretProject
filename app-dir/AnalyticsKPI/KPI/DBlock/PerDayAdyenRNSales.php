<?php
namespace KPI\DBlock;

class PerDayAdyenRNSales extends DBlockPerDay{


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            $whereFilters = "";
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(total_revenue) FROM fact_payments AS fp
            INNER JOIN dimension_time AS dt ON dt.id=fp.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fp.dimension_location_id
            INNER JOIN dimension_payment_method AS dpm ON dpm.id=fp.dimension_payment_method_id
            INNER JOIN dimension_subscription_type AS dst ON dst.id=fp.dimension_subscription_type_id
            WHERE dpm.id=201 AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." 
                AND dst.id=2 ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Adyen €RN[[Adyen RN sales - Renewal revenue processed by Adyen]]");
    }

}

?>

