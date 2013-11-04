<?php
namespace KPI\Command;

class Experiment extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        $template = new \KPI\View\Template("KPI/View/experiment.php");

        
        $perCountryTab = new \KPI\Table\Table();
        $perCountryTab->add(new \KPI\DBlock\Days())
        		->add(new \KPI\DBlock\PTEST_PerDayAdyenFOSalesTotal())
                ->add(new \KPI\DBlock\PTEST_PerDayAdyenFOSalesMen())
                ->add(new \KPI\DBlock\PTEST_PerDayAdyenFOSalesWomen())
                ->add(new \KPI\DBlock\PTEST_PerDayFO1MoPlanTotal())
                ->add(new \KPI\DBlock\PTEST_PerDayFO1MoPlanMen())
                ->add(new \KPI\DBlock\PTEST_PerDayFO1MoPlanWomen())
                ->add(new \KPI\DBlock\PTEST_PerDayFO3MoPlanTotal())
                ->add(new \KPI\DBlock\PTEST_PerDayFO3MoPlanMen())
                ->add(new \KPI\DBlock\PTEST_PerDayFO3MoPlanWomen())
                ->add(new \KPI\DBlock\PTEST_PerDayFO6MoPlanTotal())
                ->add(new \KPI\DBlock\PTEST_PerDayFO6MoPlanMen())
                ->add(new \KPI\DBlock\PTEST_PerDayFO6MoPlanWomen());
       
        $t = $perCountryTab->getTab();
        $template->set("t1Data", $t);

        $this->invoke($template);
    }
}

?>
