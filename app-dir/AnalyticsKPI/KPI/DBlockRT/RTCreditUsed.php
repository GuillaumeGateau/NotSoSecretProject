<?php
namespace KPI\DBlockRT;

class RTCreditUsed extends DBlockRT {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
        SELECT dt.id, ROUND(SUM(fpe.total_purchased))
        FROM fact_purchases_events AS fpe
            LEFT JOIN dimension_payment_method AS dpm ON dpm.id = fpe.dimension_payment_method_id
            LEFT JOIN dimension_time AS dt ON dt.id=fpe.dimension_time_id
            LEFT JOIN dimension_location AS dl ON dl.id=fpe.dimension_location_id
        WHERE dt.id = CAST(CURDATE() AS UNSIGNED) AND dpm.id IN (110) ".
            $whereFilters.
        "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Credits Used[[Total credit used in purchases]]");
    }

}

?>
