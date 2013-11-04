<?php
namespace KPI\DBlock;

class TopCountries extends DBlock {

    private $numOfCountries;

    function __construct($numOfCountries = 7,$lastDate=null) {
        parent::__construct();

        if($lastDate==null) {
            $lastDate = "CAST(DATE_SUB(CURDATE(),INTERVAL 3 DAY) AS UNSIGNED) ";
        }

        $this->qu1 = "
            SELECT country, country_code, SUM(total_revenue) FROM fact_payments AS fp
            INNER JOIN dimension_time AS dt ON dt.id=fp.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fp.dimension_location_id
            WHERE dt.id = ".$lastDate."
            GROUP BY country, country_code HAVING SUM(total_revenue)>0
            ORDER BY SUM(total_revenue) DESC
            LIMIT ".$numOfCountries
        ;
        
        $this->numOfCountries = $numOfCountries;
        $this->setName("Country");
        
    }
    
    function doExecute() { //echo $this->qu1;
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();

        $lastAvailableDate = isset($result[0]) ? $result[0] : "";

        $topCountries = array();
        $topCountriesList = array();
        foreach ($result as $key => $value) {
            $topCountries[$value[1]] = utf8_encode($value[0]);
            $topCountriesList[] = $value[1];
        }

        $this->values = $topCountries;
        $this->topCountriesList = join('\',\'', $topCountriesList);
    }

    function getTopCountriesList() {
        return isset($this->topCountriesList) ? $this->topCountriesList : "";
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
