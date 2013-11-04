<?php
namespace KPI\DBlock;

class PerDayCNNum extends DBlockPerDay{


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(num_of_cancellations) FROM fact_terminations AS ft
            INNER JOIN dimension_time AS dt ON dt.id=ft.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=ft.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("#CN[[Cancellations - Number of Premium subscriptions cancelled]]");
        $this->setGraphName("Cancellations");
        $this->setGraphVar("cn_num");
        $this->setGraphLabel("#CN");
        $this->setTConversion("1");
    }

}

?>
