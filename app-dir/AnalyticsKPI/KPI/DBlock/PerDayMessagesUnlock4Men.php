<?php
namespace KPI\DBlock;

class PerDayMessagesUnlock4Men extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(CASE WHEN dd.gender = \"MALE\" THEN 1 ELSE 0 END)
                FROM fact_purchases AS fp
                LEFT JOIN dimension_time AS dt ON dt.id = fp.dimension_time_id
                LEFT JOIN dimension_location AS dl ON dl.id = fp.dimension_location_id
                LEFT JOIN dimension_demographics AS dd ON dd.id = fp.dimension_target_demographics_id
                WHERE fp.dimension_product_id IN (4)
                AND dt.id > 20110717 AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Men Messages Unlock[[Unlocks for Men - Total number of messages unlocks by users talking to men]]");
    }

}

?>