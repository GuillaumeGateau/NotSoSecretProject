<?php
namespace KPI\DBlock;

class PerDayR2FO extends DBlockPerDay {

    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            $whereFilters = "";
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $start = \DateTime::createFromFormat('Ymd', self::$startDate);
        $dayInterval = new \DateInterval("P5D");
        $start->sub($dayInterval);
        $earlyStart = $start->format('Ymd');

        $this->qu1 = "
            SELECT dt.id AS dates, colb.regs, colc.num_fo
            FROM dimension_time AS dt

            LEFT JOIN

            (SELECT fr.dimension_time_id as dates, SUM(fr.num_of_registrations) AS regs 
            FROM fact_registrations AS fr
            INNER JOIN dimension_location AS dl ON dl.id = fr.dimension_location_id
            WHERE fr.dimension_time_id >= ".$earlyStart." AND fr.dimension_time_id <= ".self::$endDate." ".
            $whereFilters."
            GROUP BY dates
            ) AS colb
            ON colb.dates=dt.id

            LEFT JOIN

            (SELECT fs.dimension_time_id as dates, SUM(fs.num_of_subscriptions) AS num_fo 
            FROM fact_subscriptions AS fs
            INNER JOIN dimension_location AS dl ON dl.id = fs.dimension_location_id
            WHERE fs.dimension_time_id >= ".$earlyStart." AND fs.dimension_time_id <= ".self::$endDate." ".
            $whereFilters."
                AND fs.dimension_subscription_type_id=1
            GROUP BY dates
            ) AS colc
            ON colc.dates=dt.id

            WHERE dt.id >= ".$earlyStart." AND dt.id <= ".self::$endDate."
            ORDER BY dt.id DESC";

        ;

        $this->setName("%R2FO[[Reg to First Order onversion - Calculated over a running 5 days]]");
        $this->setType("float");
        $this->setASCIIName("R2FO");
        $this->setGraphName("Reg2FO conversion");
        $this->setGraphVar("r2fo");
        $this->setGraphLabel("%R2FO");
    }

    function doExecute() {
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();
        
        $trailingAmount = 5;
        $numRes = count($result)-$trailingAmount;
        for($i=0;$i<$numRes;$i++) {
            $sumRegs = 0;
            $sumFo = 0;
            for($j=0;$j<$trailingAmount;$j++) {
                $sumRegs = $result[$i+$j][1];
                $sumFo = $result[$i+$j][2];
            }
            $this->values[$result[$i][0]] = $sumRegs ? round($sumFo*100/$sumRegs,2) : "N/A";
        }

    }
}

?>
