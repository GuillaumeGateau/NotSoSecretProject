<?php
namespace KPI\DBlock;

class PerDaySmartguessUnlock extends DBlockPerDay {

    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(num_of_purchases)
            FROM fact_purchases AS fp
                LEFT JOIN dimension_time AS dt ON dt.id = fp.dimension_time_id
                LEFT JOIN dimension_location AS dl ON dl.id = fp.dimension_location_id
            WHERE fp.dimension_product_id IN (5)
                AND dt.id > 20110717 AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Smartguess Unlocks[[Smartguess Unlocks - Total number of Smartguess unlocks]]");
    }

}

?>