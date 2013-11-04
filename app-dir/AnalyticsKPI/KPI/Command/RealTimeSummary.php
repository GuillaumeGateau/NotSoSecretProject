<?php
namespace KPI\Command;

class RealTimeSummary extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        $template = new \KPI\View\Template("KPI/View/realtime_summary.php");

        // Payments are taken from the replica
        $RTTab = new \KPI\Table\Table();
        $RTTab->add(new \KPI\DBlockRT\RTDay())
                ->add(new \KPI\RBlock\RTRegs())
                ->add(new \KPI\RBlock\RTSales())
                ->add(new \KPI\RBlock\RTFONum())
                ->add(new \KPI\RBlock\RTFOSales())
                ->add(new \KPI\RBlock\RTRNNum())
                ->add(new \KPI\RBlock\RTRNSales())
                ->add(new \KPI\RBlock\RTCNNum());
        $t = $RTTab->setIncludeKeyCol(false);
        $t = $RTTab->getTab();
        $template->set("RTData", $t);
        
        $RTTab2 = new \KPI\Table\Table();
        $RTTab2->add(new \KPI\RBlock\RTPaymentProviders())
                ->add(new \KPI\RBlock\RTSalesByProvider())
                ->add(new \KPI\RBlock\RTCountByProvider());
        $t = $RTTab2->getTab();
        $template->set("RTData2", $t);
        
        $RTTab3 = new \KPI\Table\Table();
        $RTTab3->add(new \KPI\DBlockRT\RTCreditUsed())
                ->add(new \KPI\DBlockRT\RTMenMicroActions())
                ->add(new \KPI\DBlockRT\RTFemaleMicroActions())
                ->add(new \KPI\DBlockRT\RTUnlockMessages())
                ->add(new \KPI\DBlockRT\RTUnlockChat())
                ->add(new \KPI\DBlockRT\RTUnlockSmartguess())
                ->add(new \KPI\DBlockRT\RTUnlockFilmstrip())
                ->add(new \KPI\DBlockRT\RTUnlockHotseat())
                ->add(new \KPI\DBlockRT\RTNotifiersSent());
                
        $t = $RTTab3->getTab();
        $template->set("RTData3", $t);

        $this->invoke($template);
    }
}

?>
