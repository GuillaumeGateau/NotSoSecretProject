<?php
namespace KPI\Charter;

class Charter {

    private $name;
    private $csv;
    private $fname;

    function __construct($name, $csv) {
        $this->name = $name;
        $this->csv = $csv;
        $this->fname = $this->name."-".md5($this->csv);
    }

    function writeCSV() {
        $statPath = \KPI\Base\ApplicationConfig::getValue("statPath");

        $out = \file_put_contents($statPath.$this->getFileName().".csv",$this->csv,\LOCK_EX);
 
    }

    function getFileName() {
        // return unique ID for this DBlock, to store in cache

        return $this->fname;
    }

    function getCSV() {
        return $this->csv;
    }

    function getName() {
        return $this->name;
    }
    

}

?>
