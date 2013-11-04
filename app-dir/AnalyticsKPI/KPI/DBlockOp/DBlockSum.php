<?php
namespace KPI\DBlockOp;

class DBlockSum extends DBlockOp {

    private $b1;
    private $b2;
    private $precision;

    function __construct(\KPI\DBlock\DBlock $b1, \KPI\DBlock\DBlock $b2, $name, $precision = 2, $tconversion = boolean) {
        $this->b1 = $b1;
        $this->b2 = $b2;
        $this->precision = $precision;

        $this->setName($name);
        $this->setTConversion($tconversion);
    }

    function doExecute() {

        $c1 = $this->b1->getCol();

        foreach($c1 as $key=>$value) {
            $v2 = $this->b2->getV($key);
            $this->values[$key] = $v2!=0 ? ($value!=0 ? $value+$v2 : $v2) : ($value!=0 ? $value : "N/A");
        }
    }

}
?>
