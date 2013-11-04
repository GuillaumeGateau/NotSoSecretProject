<?php
namespace KPI\RBlock;

abstract class RBlockRT extends \KPI\DBlock\DBlock {


    protected $qu1;
    protected $values=array();

    function __construct() {
        parent::__construct();
        $this->setType("Replica");
        $instance = \KPI\Base\Replica_DBShop::instance();
        $this->PDO = $instance->getPDO();
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
