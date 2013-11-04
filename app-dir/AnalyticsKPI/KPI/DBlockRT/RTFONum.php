<?php
namespace KPI\DBlockRT;

class RTFONum extends DBlockRT {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(num_of_subscriptions) FROM fact_subscriptions_events AS fse
            INNER JOIN dimension_time AS dt ON dt.id=fse.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fse.dimension_location_id
            INNER JOIN dimension_subscription_type AS dst ON dst.id=fse.dimension_subscription_type_id
            WHERE dst.id=1
            AND dt.id = CAST(CURDATE() AS UNSIGNED) ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("#FO[[Number of First Orders - Total number of first orders placed today]]");
    }

}

?>
