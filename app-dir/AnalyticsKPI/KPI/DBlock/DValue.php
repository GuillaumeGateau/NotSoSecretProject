<?php
namespace KPI\DBlock;

class DValue {

    public $value;
    public $quote;
    public $keyId;
    public $id = null;
    public $props = array();
    public $tval = null;
    public $tassoc = null;
    
    function __construct($key,$val,$fval,$b){
    
        if ($b->getType() == "CB"){
            $this->value = $fval[1];
            $this->id = $fval[0];
        } else {        
            $this->value = $fval;
            $this->quote = $val;
        }
        
        $this->keyId = $key;
        
        $this->getTVal($val,$b);
        
        if ($b->getTDisable() == 1){
            return;
        }
        
        $this->getCI($val,$b,0.1);
        $this->setProps($b,"class","green","red");
        
        $this->getCI($val,$b,0.01);
        $this->setProps($b,"class","green_bold","red_bold");
            
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
            
            for ($j = 0; $j < $cols; $j++){ if ($GLOBALS["ttbl"][0][$j] == $alpha) break; }        //get col number
            
            for ($i = 0; $i < count($GLOBALS["ttbl"]); $i++){ if ($GLOBALS["ttbl"][$i][0] == $b->sN-1) break; }        //get row number
            
            $tassoc = $GLOBALS["ttbl"][$i][$j];

            //$tassoc = floatval($tassoc);
            //echo "sN: " . $b->sN . "<br>";
            //echo $tassoc . " - tval: " . $this->tval . "<br>";
            //echo "i: " . $i . " - j: " . $j . "<br><br>";
            
            $this->tassoc = $tassoc;
            
        }
        }
    }
    
    function setProps($b,$prop,$c_pos, $c_neg){
        if ($this->value == "N/A"){
            $this->props["class"] = "grey";
            return;
        }
        
        if ($this->tassoc == null){
            return;
        }
        
        if ($b->getTConversion() == 1){
            if ($this->tval > $this->tassoc){
                $this->props[$prop] = $c_neg;
            } elseif ($this->tval < ((-1)*$this->tassoc)){
                $this->props[$prop] = $c_pos;
            }
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