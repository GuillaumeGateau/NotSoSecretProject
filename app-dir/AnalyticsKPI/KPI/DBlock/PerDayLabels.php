<?php
namespace KPI\DBlock;

class PerDayLabels extends DBlock{

    private $qu1;
    protected $values=array();

    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            $whereFilters = "WHERE 1 ";
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT id,CONCAT(year,'/',month,'/',day,' (',SUBSTRING(day_name,1,3),')') from dimension_time AS dt
            WHERE id BETWEEN CAST(DATE_SUB(CURDATE(),INTERVAL ".$this->limit." DAY) AS UNSIGNED) AND CAST(DATE_SUB(CURDATE(),INTERVAL 1 DAY) AS UNSIGNED)
            ORDER BY id DESC"
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
