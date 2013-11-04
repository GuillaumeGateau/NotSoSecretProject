<?php
namespace KPI\CBlock;

class CBPDate extends CBlock {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $this->qu1 = "
            SELECT id, purch_date FROM chargebacks ORDER BY cb_date DESC"
        ;

        $this->setName("Purch. Date");
        $this->setType("CB");
    }

    function doExecute() {
    	$stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();
        
        $purch_date = array();
        $temp = array();
        foreach ($result as $key => $value) {
        	$temp[] = $value[0];
        	$temp[] = $value[1];
            //$purch_date[] = $value[1];
            array_push($purch_date, $temp);
            \array_shift($temp);
            \array_shift($temp);
        }
        
        //var_dump($purch_date);
        $this->values = $purch_date;
    }

    function isCachable() {
        return false;
    }

   function getCacheStart() {
        return 18000;
    }

    function getCachePeriod() {
        return 86400;
    }

}

?>
