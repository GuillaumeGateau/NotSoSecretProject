<?php
namespace KPI\CBlock;

class CValue {

	public $value;
	public $id = null;
	public $props = array();
	public $tval = null;
	public $tassoc = null;
	
	function __construct($val,$fval,$b){
	
		$this->value = $fval[1];
		$this->id = $fval[0];
		
		
		$this->getTVal($val,$b);
		
		$this->getCI($val,$b,0.20);
		$this->setProps("class","green","red");
		
		$this->getCI($val,$b,0.05);
		$this->setProps("class","green_bold","red_bold");
			
		return $this;

	}
	
	function getTVal($val,$b){
		if (is_numeric($val)){
			if ($b->sVar != 0){
				$tval = (sqrt($b->sN) * ($val - $b->sAvg)) / $b->sVar;
				$this->tval = $tval;
			}
		}
	}
	
	function getCI($val,$b,$alpha){
		if ($GLOBALS["ttbl"]){
		if ($this->tval != null){
		
			$cols = (count($GLOBALS["ttbl"],1)/count($GLOBALS["ttbl"],0))-1;
			
			for ($j = 0; $j < $cols; $j++){ if ($GLOBALS["ttbl"][0][$j] == $alpha) break; }		//get col number
			
			for ($i = 0; $i < count($GLOBALS["ttbl"]); $i++){ if ($GLOBALS["ttbl"][$i][0] == $b->sN-1) break; }		//get row number
			
			$tassoc = $GLOBALS["ttbl"][$i][$j];

			//$tassoc = floatval($tassoc);
			//echo "sN: " . $b->sN . "<br>";
			//echo $tassoc . " - tval: " . $this->tval . "<br>";
			//echo "i: " . $i . " - j: " . $j . "<br><br>";
			
			$this->tassoc = $tassoc;
			
		}
		}
	}
	
	function setProps($prop,$c_pos, $c_neg){
		if ($this->value == "N/A"){
			$this->props["class"] = "grey";
			return;
		}
		
		if ($this->tassoc == null){
			return;
		}
			
		if ($this->tval > $this->tassoc){
			$this->props[$prop] = $c_pos;
		} elseif ($this->tval < ((-1)*$this->tassoc)){
			$this->props[$prop] = $c_neg;
		}	
		//$this->props["tval"] = $this->tval;
	}
	
	
}
?>