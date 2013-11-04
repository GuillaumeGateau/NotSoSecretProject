<?php
namespace KPI\DBlock;

class MarketAgeGroups extends DBlock {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $this->qu1 = "
            Age groups 18-24 25-35 36-50 51+"
        ;

        $this->setName("Age groups");
    }

    function doExecute() {
        $this->values = array("18-24"=>"18-24","25-35"=>"25-35","36-50"=>"36-50","51+"=>"51+");
    }

    function isCachable() {
        return true;
    }

    function getCacheStart() {
        return 18000;
    }

    function getCachePeriod() {
        return 86400;
    }

}

?>
