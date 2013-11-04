<?php
namespace KPI\DBlockRT;

class RTSalesPayPal extends DBlockRT {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(total_revenue) FROM fact_payments_events AS fpe
            INNER JOIN dimension_time AS dt ON dt.id=fpe.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fpe.dimension_location_id
            WHERE dt.id = CAST(CURDATE() AS UNSIGNED)
            AND dimension_payment_method_id=202 ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("€ PayPal[[Paypal Sales - Successful payments through Paypal]]");
    }

}

?>
