<?php
namespace KPI\DBlockOp;

class DBlockDiv extends DBlockOp {

    private $b1;
    private $b2;
    private $precision;

    function __construct(\KPI\DBlock\DBlock $b1, \KPI\DBlock\DBlock $b2, $name, $precision = 2) {
        $this->b1 = $b1;
        $this->b2 = $b2;
        $this->precision = $precision;

        $this->setName($name);
    }

    function doExecute() {

        $c1 = $this->b1->getCol();

        foreach($c1 as $key=>$value) {
            $v2 = $this->b2->getV($key);
            $this->values[$key] = $v2!=0 ? round($value/$v2,$this->precision) : "N/A";
        }
    }

}
?>
