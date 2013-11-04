<?php
namespace KPI\Command;

class Marketing extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        $template = new \KPI\View\Template("KPI/View/marketing.php");

       

        $filter=null;
        // set source filter if any
        $source = $request->getProperty("source");
        if($source) {
            if(!$filter) {
                $filter = array();
            }
            $filter["source"] = $source;
        }

        $medium = $request->getProperty("medium");
        if($medium) {
            if(!$filter) {
                $filter = array();
            }
            $filter["medium"] = $medium;
        }

        $dates=$request->getProperty("rangePick");
        if($dates) {
            $dateRange = explode(' - ',$request->getProperty("rangePick"));
            $startDate = \str_replace('/', '', $dateRange[0]);
            $endDate = \str_replace('/', '', $dateRange[1]);           
            \KPI\DBlock\DBlockPerDay::setDateRange($startDate, $endDate);
            // Note if dates are invalid, default dates will be entered
        }
        else {
            \KPI\DBlock\DBlockPerDay::setDefaultDates();
        }

        if(empty($filter)) {
            $filter = array("source"=>"balablub","medium"=>"balablub");
        }
        else {
        
        $dayTab1 = new \KPI\Table\Table();
        $dayTab1->add(new \KPI\DBlock\MarketPerDayCountries($filter))
                ->add(new \KPI\DBlock\MarketPerDayRegs($filter))
                ->add(new \KPI\DBlock\MarketPerDayRegsGT25($filter))
                ->add(new \KPI\DBlock\MarketPerDayRegsFemales($filter))
                ->add(new \KPI\DBlock\MarketPerDayRegsDisabled($filter))
                ->add(new \KPI\DBlock\MarketPerDayRegsDisabledGT25($filter))
                ;

        $t = $dayTab1->getTab();
        $template->set("dayData1", $t);

        $dayTab2 = new \KPI\Table\Table();
        $dayTab2->add(new \KPI\DBlock\MarketPerDayCampaigns($filter))
                ->add(new \KPI\DBlock\MarketPerCampaignRegs($filter))
                ->add(new \KPI\DBlock\MarketPerCampaignRegsGT25($filter))
                ->add(new \KPI\DBlock\MarketPerCampaignRegsFemales($filter))
                ->add(new \KPI\DBlock\MarketPerCampaignRegsDisabled($filter))
                ->add(new \KPI\DBlock\MarketPerCampaignRegsDisabledGT25($filter))
                ;

        $t = $dayTab2->getTab();
        $template->set("dayData2", $t);

        $dayTab3 = new \KPI\Table\Table();
        $dayTab3->add(new \KPI\DBlock\MarketAgeGroups())
                ->add(new \KPI\DBlock\MarketPerAgeRegsMale($filter))
                ->add(new \KPI\DBlock\MarketPerAgeRegsFemale($filter))
                ->add(new \KPI\DBlock\MarketPerAgeRegsMaleDisabled($filter))
                ->add(new \KPI\DBlock\MarketPerAgeRegsFemaleDisabled($filter))
                ;

        $t = $dayTab3->getTab();
        $template->set("dayData3", $t);

        $b = new \KPI\DBlock\MarketMatchingRegs($filter);
        $col = $b->getCol();
        $regsMatching = $col[0];
        $template->set("regsMatching", $regsMatching);
        $b = new \KPI\DBlock\MarketMatchingRegsDisabled($filter);
        $col = $b->getCol();
        $regsMatchingDisabled = $col[0];
        $template->set("regsMatchingDisabled", $regsMatchingDisabled);


        }

        $b = new \KPI\DBlock\MarketTotalRegs();
        $col = $b->getCol();
        $regsTotal = $col[0];
        $template->set("regsTotal", $regsTotal);
        $b = new \KPI\DBlock\MarketTotalRegsUntracked();
        $col = $b->getCol();
        $regsUntracked = $col[0];
        $template->set("regsUntracked", $regsUntracked);
        



        $template->set("source", $source);
        $template->set("medium", $medium);
        list($startDate,$endDate) = \KPI\DBlock\DBlockPerDay::getDefaultDates();
        $dates = substr($startDate,0,4)."/".substr($startDate,4,2)."/".substr($startDate,6,2).
                " - ".substr($endDate,0,4)."/".substr($endDate,4,2)."/".substr($endDate,6,2);
        $template->set("dateRange", $dates);

        $this->invoke($template);
    }
}

?>
