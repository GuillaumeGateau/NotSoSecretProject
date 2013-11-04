<?php
namespace KPI\DBlock;

class PerDayPaymentWallSales extends DBlockPerDay{


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
            WHERE dpm.id=207 AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("€ P.Wall[[¨PaymentWall Sales - Successful payments processed through PaymentWall]]");
        $this->setGraphName("Pay.Wall Sales");
        $this->setGraphVar("pwal_sal");
        $this->setGraphLabel("€ Pay.Wall");
    }

}

?>
