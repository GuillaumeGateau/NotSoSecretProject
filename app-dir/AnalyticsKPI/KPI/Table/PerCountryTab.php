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
        $FOSales = $b->getCol();
        $b = new \KPI\DBlock\RenewalSalesPerCountry;
        $RNSales = $b->getCol();

        $countries = \KPI\DBlock\DBlockPerCountry::getTopCountries();

        $this->tab[0] = array("Country","$","FO $","RN $");

        foreach($countries as $country) {
            $this->tab[$country[0]] = array();
            $this->tab[$country[0]][0] = $country[0];
            $this->tab[$country[0]][1] = $sales[$country[1]];
            $this->tab[$country[0]][2] = $FOSales[$country[1]];
            $this->tab[$country[0]][3] = $RNSales[$country[1]];
        }

        print_r($this->tab);
    }
}

?>
