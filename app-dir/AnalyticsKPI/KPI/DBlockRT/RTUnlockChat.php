<?php
namespace KPI\DBlockRT;

class RTUnlockChat extends DBlockRT {


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
        FROM fact_purchases_events AS fpe
        LEFT JOIN dimension_time AS dt ON dt.id = fpe.dimension_time_id
        LEFT JOIN dimension_location AS dl ON dl.id = fpe.dimension_location_id
        WHERE fpe.dimension_product_id IN (1,2) AND dt.id = CAST(CURDATE() AS UNSIGNED) ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Chat Unlocks[[Chat Unlock - Total number of chat unlocks]]");
    }

}

?>