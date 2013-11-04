<?php
namespace KPI\DBlock;

class MarketMatchingRegsDisabled extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);
        
        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT SUM(num_of_registrations_disabled) AS regs FROM fact_registrations AS f
            INNER JOIN dimension_time AS dt ON dt.id=f.dimension_time_id
            INNER JOIN dimension_traffic_source AS dts ON dts.id = dimension_traffic_source_id 
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters
        ;

        $this->setName("Disabled Regs");
    }

    function doExecute() {
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();

        $countries = array();
        foreach ($result as $key => $value) {
            $countries[] = utf8_encode($value[0]);
        }

        $this->values = $countries;

    }

    function isCachable() {
        return true;
    }

    function getCacheStart() {
        return 18000;
    }

    function getCachePeriod() {
        return 86400;
    }

}

?>
