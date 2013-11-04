<?php
namespace KPI\DBlockRT;

class RTDay extends \KPI\DBlock\DBlock {

    function __construct() {
        parent::__construct();

        $this->setName("Today");
        
    }
    
    function doExecute() {
        $today = \date('Ymd');
        $todayStr = \date('D. Y/m/d');
        $this->values[$today] = $todayStr;

    }

    function isCachable() {
        return false;
    }

    function getCacheStart() {
        return null;
    }

    function getCachePeriod() {
        return 60;
    }

}

?>
