<?php
namespace KPI\Command;

class PTEST_GetRealTime extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        $template = new \KPI\View\Template("KPI/View/PTEST_get_realtime.php");
        
        $RTTab = new \KPI\Table\Table();
        $RTTab->add(new \KPI\DBlockRT\RTDay())
		        ->add(new \KPI\DBlock\PTEST_RTSalesAdyen())
                ->add(new \KPI\DBlock\PTEST_RTFONum());
        $t = $RTTab->setIncludeKeyCol(false);
        $t = $RTTab->getTab();
        $template->set("RTData", $t);

        $this->invoke($template);
    }
}

?>
