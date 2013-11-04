<?php
namespace KPI\Table;

class PerCountryTab {

    private $tab=array();

    function __construct() {
            
    }

    function build() {
        $b = new \KPI\DBlock\SalesPerCountry;
        $sales = $b->getCol();

        $b = new \KPI\DBlock\FirstOrderSalesPerCountry;
        $FOSales = $b->getCol();;
        $b = new \KPI\DBlock\RenewalSalesPerCountry;
        $RNSales = $b->getCol();;

        $countries = \KPI\DBlock\DBlockPerCountry::getTopCountries();
        
        
        $this->tab[0] = array("Country","$","FO $","RN $");

        foreach($countries as $country) {
            $r = array();
            $r[] = $country[0];
            $r[] = $sales[$country[1]];
            $r[] = $FOSales[$country[1]];
            $r[] = $RNSales[$country[1]];
            $this->tab[]=$r;
        }
    print_r($this->tab);
        
    }
}

?>
