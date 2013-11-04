<?php
namespace KPI\Table;

class CTable {

    private $CBlocks = array();
    private $tab = array();
    private $stats = array();
    private $built = false;

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

    function add(\KPI\CBlock\CBlock $db) {
        // The row with keys must be added first
        $this->CBlocks[] = $db;
        return $this;
    }

    private function build() {
        if($this->built===true) {
            return;
        } 
        


        if ($this->includeHeader === true) {
            // Get col names
            $this->tab[0] = array();
            foreach ($this->CBlocks as $b) {
                $this->tab[0][] = $b->getName();
            }
            if($this->includeKeyCol === false) {
                \array_shift($this->tab[0]);
            }
        }
        
		
       // Get col with keys and labels (left-most col)
       $keyRow = $this->CBlocks[0]->getCol();

        
        // Get each row of data, based on row key
        foreach ($keyRow as $key => $label) {
            $row = array();
            foreach ($this->CBlocks as $b) {
            	
            	if ($b->type !== "CB"){
	            	$fval = $this->format($b->getV($key),$b);
	            	$temp = new \KPI\CBlock\CValue($b->getV($key),$fval,$b);
					array_push($row,$temp);  
				} else {
					$fval = $this->format($b->getV($key[1]),$b);
	            	$temp = new \KPI\CBlock\CValue($b->getV($key[1]),$fval,$b);
					array_push($row,$temp);
				}
				
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
}
?>
