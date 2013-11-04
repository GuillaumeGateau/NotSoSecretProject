<?php
namespace KPI\Command;

class Precalc extends Command {

    function doExecute(\KPI\Controller\Request $request) {
        
        $lastDay = \KPI\DBlock\DBlock::getLastDayAvailable();

        $countryList = array('', 'fr', 'be', 'it', 'es', 'nl', 'us');

        foreach ($countryList as $country) {

            $filter = array('country_code'=>$country);

            $dayTab1 = new \KPI\Table\Table();
            $dayTab1->add(new \KPI\DBlock\LastDays())
                    ->add($s = new \KPI\DBlock\PerDaySales($filter))
                    ->add($fo = new \KPI\DBlock\PerDayFOSales($filter))
                    ->add(new \KPI\DBlock\PerDayFOPromoRate($filter))
                    ->add($s = new \KPI\DBlock\PerDayRNSales($filter))
                    ->add($foNum = new \KPI\DBlock\PerDayFONum($filter))
                    ->add(new \KPI\DBlock\PerDayRNNum($filter))
                    ->add($reg = new \KPI\DBlock\PerDayRegs($filter))
                    ->add(new \KPI\DBlockOp\DBlockDiv($s, $reg, "€/Reg", 2))
                    ->add(new \KPI\DBlockOp\DBlockDiv($fo, $reg, "€FO/Reg", 2))
                    ->add(new \KPI\DBlock\PerDayFailedPayNum($filter))
                    ->add(new \KPI\DBlock\PerDayCNNum($filter));

            $t = $dayTab1->getTab();


            $dayTab2 = new \KPI\Table\Table();
            $dayTab2->add(new \KPI\DBlock\LastDays())
                    ->add(new \KPI\DBlock\PerDayFO1WePlan($filter))
                    ->add(new \KPI\DBlock\PerDayFO1MoPlan($filter))
                    ->add(new \KPI\DBlock\PerDayFO3MoPlan($filter))
                    ->add(new \KPI\DBlock\PerDayFO6MoPlan($filter))
                    ->add(new \KPI\DBlock\PerDayFO12MoPlan($filter))
                    ->add(new \KPI\DBlock\PerDayAdyenSales($filter))
                    ->add(new \KPI\DBlock\PerDayPaylineSales($filter))
                    ->add(new \KPI\DBlock\PerDayPaymentWallSales($filter))
                    ->add(new \KPI\DBlock\PerDayPayPalSales($filter))
                    ->add(new \KPI\DBlock\PerDayiPhoneSales($filter))
                    ->add(new \KPI\DBlock\PerDayZongSales($filter))
                    ->add(new \KPI\DBlock\PerDayOTCSales($filter));
            ;

            $t = $dayTab2->getTab();



            $dayTab4 = new \KPI\Table\Table();
            $dayTab4->add(new \KPI\DBlock\LastDays())
                    ->add(new \KPI\DBlock\PerDayTotalUsers($filter))
                    ->add($vis = new \KPI\DBlock\PerDayVisits($filter))
                    ->add(new \KPI\DBlock\PerDayNewVisits($filter))
                    ->add($logs = new \KPI\DBlock\PerDayLogins($filter))
                    ->add($pv = new \KPI\DBlock\PerDayPV($filter))
                    ->add(new \KPI\DBlock\PerDayPayPV($filter))
                    ->add(new \KPI\DBlockOp\DBlockDiv($pv, $vis, "#PV/Visit"))
                    ->add(new \KPI\DBlockOp\DBlockDiv(new \KPI\DBlock\PerDayTimeOnSite($filter), $vis, "Time/Visit"))
            ;

            $t = $dayTab4->getTab();


            $dayTab5 = new \KPI\Table\Table();
            $dayTab5->add(new \KPI\DBlock\LastDays())
                    ->add($reg)
                    ->add(new \KPI\DBlock\PerDayFBRegs($filter))
                    ->add(new \KPI\DBlock\PerDayiPhoneRegs($filter))
                    ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayFemRegs($filter), $reg, "%Fem"))
                    ->add(new \KPI\DBlockOp\DBlockPercent($reg, $vis, "V2R"))
                    ->add(new \KPI\DBlock\PerDayV2RFB($filter))
                    ->add(new \KPI\DBlock\PerDayV2RGG($filter))
                    ->add(new \KPI\DBlockOp\DBlockDiv(new \KPI\DBlock\PerDayMsgNum($filter), $logs, "Msgs/Log"))
                    ->add(new \KPI\DBlockOp\DBlockDiv(new \KPI\DBlock\PerDayWinkNum($filter), $logs, "Winks/Log"))
            ;

            $t = $dayTab5->getTab();

            $perMonthTab = new \KPI\Table\Table();
            $perMonthTab->add(new \KPI\DBlock\LastMonths())
                    ->add(new \KPI\DBlock\PerMonthSales($filter))
                    ->add(new \KPI\DBlock\PerMonthFOSales($filter))
                    ->add(new \KPI\DBlock\PerMonthRNSales($filter))
                    ->add(new \KPI\DBlock\PerMonthRegs($filter))
            ;
            $t = $perMonthTab->getTab();


        }

        $perCountryTab = new \KPI\Table\Table();
        $perCountryTab->add(new \KPI\DBlock\TopCountries(7, $lastDay))
                ->add(new \KPI\DBlock\PerCountrySales())
                ->add(new \KPI\DBlock\PerCountryFirstOrderSales())
                ->add(new \KPI\DBlock\PerCountryFOPromoRate())
                ->add(new \KPI\DBlock\PerCountryRenewalSales())
                ->add(new \KPI\DBlock\PerCountryFirstOrderNum())
                ->add(new \KPI\DBlock\PerCountryRenewalNum())
                ->add(new \KPI\DBlock\PerCountryRegs())
                ->add(new \KPI\DBlockOp\DBlockDiv(new \KPI\DBlock\PerCountryThreeDaysSales(), new \KPI\DBlock\PerCountryThreeDaysRegs(), "€/Reg", 2))
                ->add(new \KPI\DBlockOp\DBlockDiv(new \KPI\DBlock\PerCountryThreeDaysFOSales(), new \KPI\DBlock\PerCountryThreeDaysRegs(), "€FO/Reg", 2))
                ->add(new \KPI\DBlock\PerCountryFailedPayments());
        $t = $perCountryTab->getTab();

        $perSourceTab = new \KPI\Table\Table();
        $perSourceTab->add(new \KPI\DBlock\TopSources(20,$lastDay))
                ->add(new \KPI\DBlock\PerSourceRegsFem())
                ->add(new \KPI\DBlock\PerSourceRegsHom())
                ->add(new \KPI\DBlock\PerSourceFOSales())
                ->add(new \KPI\DBlock\PerSourceRNSales())
                ->add(new \KPI\DBlock\PerSourceFONum())
                ->add(new \KPI\DBlock\PerSourceRNNum());

        $t = $perSourceTab->getTab();
    }

}

?>
