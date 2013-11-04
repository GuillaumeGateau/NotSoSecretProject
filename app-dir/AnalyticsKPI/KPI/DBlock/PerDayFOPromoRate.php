<?php
namespace KPI\DBlock;

class PerDayFOPromoRate extends DBlockPerDay{

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
            SELECT dt.id,ROUND(SUM(num_of_subscriptions_with_promo)*100/SUM(num_of_subscriptions),2) as pc FROM fact_subscriptions AS fs
            INNER JOIN dimension_time AS dt ON dt.id = fs.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id = fs.dimension_location_id
            INNER JOIN dimension_subscription_type AS dst ON dst.id = fs.dimension_subscription_type_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("%PROMO[[% Promo - Percentage of First Orders and Renewals with a promo code]]");
        $this->setGraphName("% of Promo subscriptions");
        $this->setGraphVar("pc_promo");
        $this->setGraphLabel("%Promo");
    }

}

?>
