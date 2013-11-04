<?php
namespace KPI\DBlockRT;

abstract class DBlockRT extends \KPI\DBlock\DBlock {


    protected $qu1;
    protected $values=array();

    function __construct() {
        parent::__construct();
    }

    function doExecute() {
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();

        $temp = array();
        foreach($result as $key=>$value) {
            $this->values[$value[0]] = $value[1];
        }
    }

    function isCachable() {
        return true;
    }

    function getCacheStart() {
        return null;
    }

    function getCachePeriod() {
        return 300;
    }
}

?>
