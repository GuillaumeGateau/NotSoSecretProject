<?php
namespace KPI\DBlockRT;

class RTFailedPaymentsNum extends DBlockRT {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(num_of_fails) FROM fact_payments_events AS fse
            INNER JOIN dimension_time AS dt ON dt.id=fse.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fse.dimension_location_id
            WHERE dt.id = CAST(CURDATE() AS UNSIGNED) ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("#Failed Payments[[#Failed Payments - Number of failed payments today]]");
        $this->setTConversion("1");
    }

}

?>
