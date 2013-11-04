<?php
namespace KPI\DBlock;

class PerDayPercentCancellations extends DBlockPerDay{


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

        SELECT dt.id , ROUND((((cancels.cancels)/(cancels.cancels + renewals.renewals))*100), 2)
        FROM dimension_time as dt
        LEFT JOIN (
            SELECT fs.dimension_time_id AS dates, SUM(fs.num_of_subscriptions) AS renewals
            FROM fact_subscriptions AS fs
            INNER JOIN dimension_location AS dl ON dl.id = fs.dimension_location_id
            WHERE fs.dimension_subscription_type_id = 2 AND fs.dimension_time_id >= ".self::$startDate." AND fs.dimension_time_id <= ".self::$endDate." ".
           $whereFilters.
           "GROUP BY dates
            ORDER BY dates DESC) AS renewals
        ON renewals.dates = dt.id
        LEFT JOIN (
            SELECT ft.dimension_time_id AS dates, SUM(ft.num_of_cancellations) AS cancels
            FROM fact_terminations AS ft
            INNER JOIN dimension_location AS dl ON dl.id = ft.dimension_location_id
            WHERE ft.dimension_time_id >= ".self::$startDate." AND ft.dimension_time_id <= ".self::$endDate." ".
           $whereFilters.
           "GROUP BY dates
            ORDER BY dates DESC) AS cancels
            ON dt.id = cancels.dates
            ORDER BY dt.id DESC"
       ;

       $this->setName("%Cancellations [[%Cancellations - %Cancellations of plans that are to renew (there is a bias because we include early cancellations)]]");
       $this->setGraphName("%Cancellations");
       $this->setGraphVar("%Cancellations");
       $this->setGraphLabel("%Cancellations");
       $this->setTConversion("1");
   }

}

?>