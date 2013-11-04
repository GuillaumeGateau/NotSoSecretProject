<?php
namespace KPI\DBlock;

// Retrieve Sales
// For most important countries
class SalesPerCountry extends DBlock{

    private $qu1;
    protected $values=array();

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT country, SUM(total_revenue) FROM fact_payments AS fp
            INNER JOIN dimension_time AS dt ON dt.id=fp.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fp.dimension_location_id
            WHERE year*10000+month*100+day = CAST(DATE_SUB(CURDATE(),INTERVAL 1 DAY) AS UNSIGNED)
            GROUP BY country HAVING SUM(total_revenue)>0
            ORDER BY SUM(total_revenue) DESC
            LIMIT 10
        ";
    }

    function doExecute() {
        $sales=array();
        $topCountries=array();

        $stmt = self::$PDO->query($this->qu1);
        $result = $stmt->fetchAll();

        $topCountries = array();
        foreach($result as $key=>$value) {
            $topCountries[] = $value[0];
            $this->values[$value[0]] = $value[1];
        }

        self::setTopCountries($topCountries);
    }
}

?>
