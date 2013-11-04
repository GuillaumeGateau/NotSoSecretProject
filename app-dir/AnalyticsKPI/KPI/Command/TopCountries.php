<?php
namespace KPI\Command;

class TopCountries extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        $template = new \KPI\View\Template("KPI/View/top_countries.php");

        $lastDay = \KPI\DBlock\DBlock::getLastDayAvailable();
        $perCountryTab = new \KPI\Table\Table();
        $perCountryTab->add(new \KPI\DBlock\TopCountries(7,$lastDay))
                ->add(new \KPI\DBlock\PerCountrySales())
                ->add(new \KPI\DBlock\PerCountryFirstOrderSales())
                ->add(new \KPI\DBlock\PerCountryFOPromoRate())
                ->add(new \KPI\DBlock\PerCountryRenewalSales())
                ->add(new \KPI\DBlock\PerCountryFirstOrderNum())
                ->add(new \KPI\DBlock\PerCountryRenewalNum())
                ->add(new \KPI\DBlock\PerCountryRegs())
                ->add(new \KPI\DBlockOp\DBlockDiv(new \KPI\DBlock\PerCountryThreeDaysSales(), new \KPI\DBlock\PerCountryThreeDaysRegs(), "€/Reg[[Euro per Reg - Calculated on a trailing 3 day basis]]", 2))
                ->add(new \KPI\DBlockOp\DBlockDiv(new \KPI\DBlock\PerCountryThreeDaysFOSales(), new \KPI\DBlock\PerCountryThreeDaysRegs(), "€FO/Reg[[FO Euro per Reg - Calculated on a trailing 3 day basis]]", 2))
                ->add(new \KPI\DBlock\PerCountryFailedPayments());
        $t = $perCountryTab->getTab();
        $template->set("t1Data", $t);
        
        $date = \DateTime::createFromFormat('Ymd', $lastDay);
        $template->set("lastDay", $date->format('D. Y/m/d'));

        $this->invoke($template);
    }
}

?>
