<?php
namespace KPI\DBlockOp;

abstract class DBlockOp extends \KPI\DBlock\DBlock {


    function __construct() {
    }

    function isCachable() {
        return false;
    }

    function getCacheStart() {
        return null;
    }

    function getCachePeriod() {
        return null;
    }
    
}
?>
