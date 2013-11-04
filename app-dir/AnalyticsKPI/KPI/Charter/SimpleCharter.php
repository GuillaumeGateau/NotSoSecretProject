<?php
namespace KPI\Charter;

class SimpleCharter extends Charter {

    private $do;

    function __construct($name, $csv, $metric, $startDate, $endDate) {
        parent::__construct($name, $csv);

        $statPath = \KPI\base\ApplicationConfig::getValue("statPath");
        $baseName = $this->getFileName();
        $metricLC = \strtolower($metric);
        $startDate = substr($startDate,0,4)."/".substr($startDate,4,2)."/".substr($startDate,6,2);
        $endDate = substr($endDate,0,4)."/".substr($endDate,4,2)."/".substr($endDate,6,2);

        $this->do = 
                "#delimit ;\n"
                . "clear;\n"
                . "cd {$statPath};\n"
                . "insheet using {$baseName}.csv, delimiter(\";\");\n"
                . "gen dat=date(day,\"YMD\");\n"
                . "format dat %td;\n"
                . "graph twoway line {$metricLC} dat || fpfit {$metricLC} dat ||,"
                . "ytitle(\"{$name}\")"
                . "xtitle(\"Dates\")"
                . "title(\"Trend and Actual {$name}\")"
                . "subtitle(\"From {$startDate} to {$endDate}\")"
                . "legend(label(1 \"{$name}\") label(2 \"Trend\"));\n"
                . "graph export {$baseName}.eps, replace;\n"
                . "shell convert -geometry 900x600 -density 900 {$baseName}.eps {$baseName}.png;\n"
                . "#delimit cr\n";
    }
    
    

    function writeDoFile() {
        $statPath = \KPI\base\ApplicationConfig::getValue("statPath");
        \file_put_contents($statPath.$this->getFileName().".do",$this->do,\LOCK_EX);
    }

    function runDoFile() {
        $statPath = \KPI\base\ApplicationConfig::getValue("statPath");
        $stataInstPath = \KPI\base\ApplicationConfig::getValue("stataInstPath");
        $cmd = $stataInstPath."stata -b do ".$statPath.$this->getFileName();

        \system($cmd);
    }

    function checkImgExists() {
        $statPath = \KPI\base\ApplicationConfig::getValue("statPath");
        $filemtime = @filemtime($statPath.$this->getFileName().".png"); // num of seconds since 1970
        if(!$filemtime) {
            // file no exist.
            return false;
        }
        return true;
    }

    function run() {
        if($this->checkImgExists()) {
            // image already exists
            return;
        }
        $this->writeCSV();
        $this->writeDoFile();
        $this->runDoFile();
    }

}

?>
