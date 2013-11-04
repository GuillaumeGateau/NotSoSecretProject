<?php
namespace KPI\DBlockRT;

class RTTopCountries extends DBlockRT {

    private $numOfCountries;
    private $RTtopCountriesList;

    function __construct($numOfCountries = 10) {
        parent::__construct();

        $this->qu1 = "
            SELECT country, country_code, SUM(total_revenue) FROM fact_payments_events AS fpe
            INNER JOIN dimension_time AS dt ON dt.id=fpe.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fpe.dimension_location_id
            WHERE dt.id = CAST(CURDATE() AS UNSIGNED)
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
        $this->RTtopCountriesList = join('\',\'', $topCountriesList);
    }

    function getTopCountriesList() {
        return isset($this->RTtopCountriesList) ? $this->RTtopCountriesList : "";
    }


}

?>
