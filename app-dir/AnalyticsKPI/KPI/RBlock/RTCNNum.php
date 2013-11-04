<?php
namespace KPI\RBlock;

class RTCNNum extends RBlockRT {

    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
         SELECT CAST(CURDATE() AS UNSIGNED) AS dimension_time_id,
          COUNT(distinct user_id)  AS total_cancellations
          FROM subscriptions WHERE DATE(cancelled_at) = DATE(NOW())"
            ;

        $this->setName("#CN[[Cancellations - Number of cancellations made today]]");
        $this->setTConversion("1");
    }

}

?>