<?php
namespace KPI\Command;

class Charts extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        $template = new \KPI\View\Template("KPI/View/charts.php");



        // set country filter if any
        $countryFilter = $request->getProperty("countryFilter");
        if($countryFilter && $countryFilter!="all") {
            $filter = array("country_code"=>$countryFilter);
        }
        else {
            $filter = null;
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
            \KPI\DBlock\DBlockPerDay::setDateRange("20110401", "20110710");
        }

        $dataBlock=$request->getProperty("dataBlock");
        if($dataBlock) {

            // get correct DBlock object
        }
        else {
            
        }

        
        

        $template->set("countryFilter", $countryFilter);
        list($startDate,$endDate) = \KPI\DBlock\DBlockPerDay::getDefaultDates();
        $dates = substr($startDate,0,4)."/".substr($startDate,4,2)."/".substr($startDate,6,2).
                " - ".substr($endDate,0,4)."/".substr($endDate,4,2)."/".substr($endDate,6,2);
        $template->set("dateRange", $dates);
        $template->set("dataBlock", $dataBlock);

        $this->invoke($template);
    }
}

?>
