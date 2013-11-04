<?php
namespace KPI\Command;

class Details extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        $template = new \KPI\View\Template("KPI/View/details.php");

        $lastDay = \KPI\DBlock\DBlock::getLastDayAvailable();
        
        $plansTab = new \KPI\Table\Table();
        $plansTab->add(new \KPI\DBlock\Days())
                 ->add(new \KPI\DBlock\Credits\PerDayCreditPlansLow())
                 ->add(new \KPI\DBlock\Credits\PerDayCreditPlansMedium())
                 ->add(new \KPI\DBlock\Credits\PerDayCreditPlansHigh());
        $t = $plansTab->getTab();
        $template->set("creditPlansData", $t);
        
        $date = \DateTime::createFromFormat('Ymd', $lastDay);
        $template->set("lastDay", $date->format('D. Y/m/d'));

        $this->invoke($template);
    }
}

?>
