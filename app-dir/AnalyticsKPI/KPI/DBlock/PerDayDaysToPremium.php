<?php
namespace KPI\DBlock;



class PerDayDaysToPremium extends DBlockPerDay {

    private $whereFilters;

    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->whereFilters = $whereFilters;

        $this->qu1 = "
            SELECT dt.id,SUM(num_of_page_views) FROM fact_site_stats AS fss
            INNER JOIN dimension_time AS dt ON dt.id=fss.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fss.dimension_location_id
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;
        
        $this->setName("Days2PM[[Avg. Days to PM - For a day D, avg. of people who have registered between D-10 and D-6 days, and have paid before D]]");
        $this->setType("float");
        $this->setGraphName("Avg. days to Premium");
        $this->setGraphVar("d2pm");
        $this->setGraphLabel("Days2PM");
        $this->setTConversion(true);
    }

    function doExecute() {

        $start = \DateTime::createFromFormat('Ymd', self::$startDate);
        $end = \DateTime::createFromFormat('Ymd', self::$endDate);
        $dayInterval = new \DateInterval("P1D");
        for ($end; $end >= $start; $end->sub($dayInterval)) {
            $day = $end->format('Ymd');
            $this->qu1 = "
           SELECT
                ROUND(SUM(avgs.dtp)/SUM(avgs.payments), 2)
           FROM (SELECT
                    payments.regday AS reg
                    , payments.payments AS payments
                    , DATEDIFF(DATE(payments.payday), DATE(payments.regday))*payments AS dtp
                 FROM (SELECT
                            fact_payments.dimension_time_id AS payday
                            , fact_payments.dimension_registration_time_id AS regday
                            , fact_payments.num_of_payments AS payments
                       FROM fact_payments
                       INNER JOIN dimension_location AS dl on dl.id=dimension_location_id
                       WHERE fact_payments.dimension_registration_time_id != 0 AND fact_payments.num_of_payments>0 AND fact_payments.total_revenue>0 
                       ".$this->whereFilters.") AS payments
                 WHERE DATE(payments.regday) > (DATE(".$day.") - INTERVAL 10 DAY) AND DATE(payments.regday) < (DATE(".$day.") - INTERVAL 6 DAY) AND DATE(payments.payday) < DATE(".$day.")
                 ORDER BY payments.regday DESC) AS avgs
            "
            ;
           
            $stmt = $this->PDO->query($this->qu1);
            $result = $stmt->fetch();

            
            $this->values[$day] = isset($result[0]) ? $result[0] : "N/A";

        }
        
    }

}

?>
