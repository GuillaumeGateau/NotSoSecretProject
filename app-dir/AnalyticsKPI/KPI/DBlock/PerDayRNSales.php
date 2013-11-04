<?php
namespace KPI\DBlock;

class PerDayRNSales extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id,SUM(total_revenue) FROM fact_payments AS fp
            INNER JOIN dimension_time AS dt ON dt.id=fp.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fp.dimension_location_id
            INNER JOIN dimension_subscription_type AS dst ON dst.id=fp.dimension_subscription_type_id
            WHERE dst.id = 2 AND dimension_payment_method_id NOT IN (211, 212, 213, 214, 215) 
                AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;
        
        $this->setName("€RN[[Renewal sales]]");
        $this->setASCIIName("RN_sales");
        $this->setType("float");
        $this->setGraphName("Renewal sales");
        $this->setGraphVar("rn_sal");
        $this->setGraphLabel("€RN");
    }

}

?>
