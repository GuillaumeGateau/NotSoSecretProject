<?php
namespace KPI\DBlock;

class PerMonthFOSales extends DBlockPerMonth {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT SUBSTRING(dimension_time_id,1,6), SUM(total_revenue) FROM fact_payments AS fp
INNER JOIN dimension_time AS dt ON dt.id = fp.dimension_time_id
INNER JOIN dimension_location AS dl ON dl.id=fp.dimension_location_id
WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate.
                " AND (dimension_subscription_type_id=1 OR dimension_subscription_type_id=4) ".
            $whereFilters."
group by year, SUBSTRING(dimension_time_id,1,6), month
order by year desc, month desc;"
        ;

        $this->setName("€FO[[FO Sales - Revenue from First Orders]]");
        $this->setType("float");
    }

}

?>
