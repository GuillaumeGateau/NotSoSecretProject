<?php
namespace KPI\CBlock;

class CBAmount extends CBlock {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $this->qu1 = "
            SELECT id, amount FROM chargebacks ORDER BY cb_date DESC"
        ;

//        $this->setName("Amount<br>(in EUR)");
        $this->setName("Amount");
        $this->setType("CB");
    }

    function doExecute() {
    	$stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();
        
        $cbAmount = array();
        $temp = array();
        foreach ($result as $key => $value) {
            $temp[] = $value[0];
        	$temp[] = $value[1];
        	
            array_push($cbAmount,$temp);
            \array_shift($temp);
            \array_shift($temp);
        }
        $this->values = $cbAmount;
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
