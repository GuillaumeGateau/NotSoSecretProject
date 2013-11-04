<?php
namespace KPI\Command;

// Include all the classes
include("KPI/pChart2.1.1/class/pDraw.class.php");
include("KPI/pChart2.1.1/class/pImage.class.php");
include("KPI/pChart2.1.1/class/pData.class.php");



class GenChart extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        $template = new \KPI\View\Template("KPI/View/gen_chart.php");



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
            \KPI\DBlock\DBlockPerDay::setDefaultDates();
        }

        $dataBlock=$request->getProperty("dataBlock");
        if($dataBlock) {

            // get correct DBlock object
            switch ($dataBlock) {
                case "Registrations":
                    $db = new \KPI\DBlock\PerDayRegs($filter);
                    $metric = "regs";
                    $name = "Registrations";
                    break;
                case "Sales":
                    $db = new \KPI\DBlock\PerDaySales($filter);
                    $metric = "sales";
                    $name = "Sales";
                    break;
                case "FO_sales":
                    $db = new \KPI\DBlock\PerDayFOSales($filter);
                    $metric = "fo_sales";
                    $name = "FO_revenue";
                    break;
                case "RN_sales":
                    $db = new \KPI\DBlock\PerDayRNSales($filter);
                    $metric = "rn_sales";
                    $name = "RN_revenue";
                    break;
                case "R2FO":
                    $db = new \KPI\DBlock\PerDayR2FO($filter);
                    $metric = "R2FO";
                    $name = "Regs2FO";
                    break;
            }
        }
        else {
            
        }

        $dayTab1 = new \KPI\Table\Table();
        $dayTab1->add(new \KPI\DBlock\Days())
                ->add($db)
               ;

        $csv = $dayTab1->getCSV();
       
        $chart = new \KPI\Charter\SimpleCharter($name, $csv, $metric, $startDate, $endDate);
        $chart->run();

        $imgFile = $chart->getFileName().".png";

        $template->set('imgFile', $imgFile);
        $template->set('randNum',  $randNum=\mt_rand());
        
        $this->invoke($template);
    }
}

?>
