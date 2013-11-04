<?php
namespace KPI\DBlock;

class PerDayAvgToPremium extends DBlock{

    private $qu1;
    protected $values=array();

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
            SELECT dt.id, SUM(avg_days_to_premium*num_of_subscriptions)/SUM(num_of_subscriptions) AS avg FROM fact_subscriptions AS fs
            INNER JOIN dimension_time AS dt ON dt.id=fs.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fs.dimension_location_id
            INNER JOIN dimension_subscription_type AS dst ON dst.id=fs.dimension_subscription_type_id
            WHERE dst.id=1
                AND dt.id >= CAST(DATE_SUB(CURDATE(),INTERVAL ".$this->limit." DAY) AS UNSIGNED) ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;
    }

    function doExecute() {
        $stmt = self::$PDO->query($this->qu1);
        $result = $stmt->fetchAll();

        foreach($result as $key=>$value) {
            $this->values[$value[0]] = $value[1];
        }
    }
}

?>
