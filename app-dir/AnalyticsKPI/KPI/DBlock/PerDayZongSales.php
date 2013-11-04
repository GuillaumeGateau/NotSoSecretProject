<?php
namespace KPI\DBlock;

class PerDayZongSales extends DBlockPerDay{


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
            SELECT dt.id, SUM(total_revenue) FROM fact_payments AS fpe
            LEFT JOIN dimension_time AS dt ON dt.id=fpe.dimension_time_id
            LEFT JOIN dimension_location AS dl ON dl.id=fpe.dimension_location_id
            LEFT JOIN dimension_payment_method AS dpm ON dpm.id=fpe.dimension_payment_method_id
            WHERE dpm.id IN (208, 213) AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("€ Zong[[Zong sales - Successful payments processed through Zong]]");
        $this->setGraphName("Zong sales");
        $this->setGraphVar("zong_sal");
        $this->setGraphLabel("€ Zong");
    }

}

?>
