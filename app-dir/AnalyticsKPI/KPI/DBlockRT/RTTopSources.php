<?php
namespace KPI\DBlockRT;

class RTTopSources extends DBlockRT {

    private $numOfSources;

    function __construct($numOfSources = 20) {
        parent::__construct();

        $this->qu1 = "
            SELECT IF(source='(direct)','UNKNOWN',IF(source IS NULL,'UNKNOWN',source)) as src, SUM(num_of_registrations) FROM fact_registrations_events AS fp
            INNER JOIN dimension_time AS dt ON dt.id=fp.dimension_time_id
            INNER JOIN dimension_traffic_source AS dts ON dts.id=fp.dimension_traffic_source_id
            WHERE dt.id = CAST(CURDATE() AS UNSIGNED)
            GROUP BY src HAVING SUM(num_of_registrations)>0
            ORDER BY SUM(num_of_registrations) DESC
            LIMIT ".$numOfSources
        ;
        
        $this->numOfSources = $numOfSources;
        $this->setName("Source");
        
    }
    
    function doExecute() {
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();

        $lastAvailableDate = isset($result[0]) ? $result[0] : "";

        $topSources = array();
        foreach ($result as $key => $value) {
            $val = $value[0] != '' ? utf8_encode($value[0]) : "(unknown)"; // no longer useful because NULL source eliminated in query
            $topSources[$val] = $val;
        }
        
        $this->values = $topSources;
    }


}

?>
