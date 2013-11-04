<?php
namespace KPI\Command;

class GetRealTime extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        $template = new \KPI\View\Template("KPI/View/get_realtime.php");
        
        $RTTab = new \KPI\Table\Table();
        $RTTab->add(new \KPI\DBlockRT\RTDay())
                ->add(new \KPI\DBlockRT\RTSales())
                ->add(new \KPI\DBlockRT\RTRegs())
                ->add(new \KPI\DBlockRT\RTFONum())
                ->add(new \KPI\DBlockRT\RTRNNum())
                ->add(new \KPI\DBlockRT\RTCNNum())
                ->add(new \KPI\DBlockRT\RTCreditSales())
                ->add(new \KPI\DBlockRT\RTCreditFO())
                ->add(new \KPI\DBlockRT\RTZong());
        $t = $RTTab->setIncludeKeyCol(false);
        $t = $RTTab->getTab();
        $template->set("RTData", $t);

        $RTTab = new \KPI\Table\Table();
        $RTTab->add(new \KPI\DBlockRT\RTDay())
                ->add(new \KPI\DBlockRT\RTSalesAdyen())
                ->add(new \KPI\DBlockRT\RTSalesPayline())
                ->add(new \KPI\DBlockRT\RTSalesPaymentWall())
                ->add(new \KPI\DBlockRT\RTSalesPayPal())
                ->add(new \KPI\DBlockRT\RTSalesApple())
                ->add(new \KPI\DBlockRT\RTSalesZong());
        $t = $RTTab->setIncludeKeyCol(false);
        $t = $RTTab->getTab();
        $template->set("RTData2", $t);
        
        $RTTab = new \KPI\Table\Table();
        $RTTab->add(new \KPI\DBlockRT\RTTopCountries())
                ->add(new \KPI\DBlockRT\RTPerCountrySales())
                ->add(new \KPI\DBlockRT\RTPerCountryFONum())
                ->add(new \KPI\DBlockRT\RTPerCountryRNNum())
                ->add($regs = new \KPI\DBlockRT\RTPerCountryRegs())
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlockRT\RTPerCountryRegsFem(),$regs,"%Fem",2))
                ->add(new \KPI\DBlockRT\RTPerCountryRegs18_24())
                ->add(new \KPI\DBlockRT\RTPerCountryRegs25_35())
                ->add(new \KPI\DBlockRT\RTPerCountryRegs36_50())
                ->add(new \KPI\DBlockRT\RTPerCountryRegsGT50())
                ;

        $t = $RTTab->getTab();
        $template->set("RTData3", $t);

        $RTTab = new \KPI\Table\Table();
        $RTTab->add(new \KPI\DBlockRT\RTTopSources())
                ->add(new \KPI\DBlockRT\RTPerSourceSales())
                ->add(new \KPI\DBlockRT\RTPerSourceFONum())
                ->add(new \KPI\DBlockRT\RTPerSourceRNNum())
                ->add(new \KPI\DBlockRT\RTPerSourceRegs())
                ->add(new \KPI\DBlockRT\RTPerSourceRegsLT24())
                ->add(new \KPI\DBlockRT\RTPerSourceRegs25_35())
                ->add(new \KPI\DBlockRT\RTPerSourceRegs36_50())
                ->add(new \KPI\DBlockRT\RTPerSourceRegsGT50())
                ->add(new \KPI\DBlockRT\RTPerSourceCountries())
                ;

        

        $t = $RTTab->getTab();
        $template->set("RTData4", $t);

        $this->invoke($template);
    }
}

?>
