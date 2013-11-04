<?php
namespace KPI\Table;

class Table {

    private $dBlocks = array();
    private $tab = array();
    private $stats = array();
    private $tabCSV = "";
    private $built = false;
    private $builtCSV = false;

    private $includeHeader = true;
    private $includeKeyCol = true;
    
    private $sN = 0;
    private $sAvg = 0;
    private $sVar = 0;

    function __construct() {
    }

    function setIncludeHeader($bool) {
        $this->includeHeader = $bool;
    }

    function setIncludeKeyCol($bool) {
        $this->includeKeyCol = $bool;
    }

    function add(\KPI\DBlock\DBlock $db) {
        // The row with keys must be added first
        $this->dBlocks[] = $db;
        $this->built = $this->builtCSV = false;

        return $this;
    }

    private function build($csv=false) {
        if($this->built===true) {
            return;
        } 
        

        if ($this->includeHeader === true) {
            // Get col names
            $this->tab[0] = array();
            foreach ($this->dBlocks as $b) {
                $this->tab[0][] = $csv==false ? $b->getName() : $b->getASCIIName();
            }
            if($this->includeKeyCol === false) {
                \array_shift($this->tab[0]);
            }
        }
        
        
       // Get col with keys and labels (left-most col)
       $keyRow = $this->dBlocks[0]->getCol();

        // Get each row of data, based on row key
        foreach ($keyRow as $key => $label) {

            $row = array();
            foreach ($this->dBlocks as $b) {
                
                if ($b->type !== "CB"){
                    $fval = $this->format($b->getV($key),$b);
                    $temp = new \KPI\DBlock\DValue($key,$b->getV($key),$fval,$b);
                    array_push($row,$temp);  
                } else {
                    $fval = $this->format($b->getV($key[1]),$b);
                    $temp = new \KPI\DBlock\DValue($key,$b->getV($key[1]),$fval,$b);
                    array_push($row,$temp);
                }
                
               // $row[] = $csv==false ? $this->format($b->getV($key),$b) : $b->getV($key);

            }
            if($this->includeKeyCol === false) {
                \array_shift($row);
            }
            $this->tab[] = $row;
        }
        //var_dump($this->tab);
    }

    function getTab() {
        if(!$this->built) {
            $this->build();
            $this->built = true;
        }
        return $this->tab;
    }
    
     function format($val,$block) {
        if(\is_numeric($val)) {
            if(\is_float($val) || $block->getType()=='float') {
                return \number_format($val,2,"."," ");
            }
            elseif(\is_int($val) || $block->getType()=='int') {
                return \number_format($val,0,"."," ");
            }
            else {
                return $val;
            }
        }
        else {
            return $val;
        }
    }

    function getCSV() {
        if (!$this->builtCSV) {
            $this->build(true);

            $csvData = "";
            if ($this->includeHeader === true) {
                $lblRow = \array_shift($this->tab);
                foreach($lblRow as $label) {
                    $arr = \explode("[[", $label);
                    $csvData .= '"'.$arr[0].'";';
                }
                $csvData = \rtrim($csvData,";");
                $csvData .= "\n";
            }
            
            foreach ($this->tab as $row) {
                foreach ($row as $val) {
                    if(\preg_match("/^[a-zA-Z]{3}\\. [0-9\\/]{10}$/", $val)) {
                        $csvData .= \substr($val, -10);
}
                    else {
                        $csvData .= \is_numeric($val) ? $val : '"' . $val . '"';
                    }
                    $csvData .= ';';
                }
                $csvData = \rtrim($csvData,";");
                $csvData .= "\n";
            }

            $this->tabCSV = $csvData;
            $this->builtCSV = true;
        }

        return $csvData;
    }

}

?>
