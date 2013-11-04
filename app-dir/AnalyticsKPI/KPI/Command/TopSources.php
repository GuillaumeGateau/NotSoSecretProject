<?php
namespace KPI\Command;

class TopSources extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        $template = new \KPI\View\Template("KPI/View/top_sources.php");

        $lastDay = \KPI\DBlock\DBlock::getLastDayAvailable();
        $perSourceTab = new \KPI\Table\Table();
        $perSourceTab->add(new \KPI\DBlock\TopSources(20,$lastDay))
                ->add($regs = new \KPI\DBlock\PerSourceRegs())
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerSourceRegsFem(), $regs, "%Fem[[Fem. registrations - Percentage of female registrations]]", 2))
                ->add(new \KPI\DBlock\PerSourceRegsDisabled())
                ->add(new \KPI\DBlock\PerSourceFOSales())
                ->add(new \KPI\DBlock\PerSourceRNSales())
                ->add(new \KPI\DBlock\PerSourceFONum())
                ->add(new \KPI\DBlock\PerSourceRNNum())
                ->add(new \KPI\DBlock\PerSourceCountries());
               
        $t = $perSourceTab->getTab();
        $template->set("t1Data", $t);
        
        $date = \DateTime::createFromFormat('Ymd', $lastDay);
        $template->set("lastDay", $date->format('D. Y/m/d'));

        $this->invoke($template);
    }
}

?>
