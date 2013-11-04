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
                    break;
                case "Sales":
                    $db = new \KPI\DBlock\PerDaySales($filter);
                    break;
            }
        }
        else {
            
        }

        $dayTab1 = new \KPI\Table\Table();
        $dayTab1->add(new \KPI\DBlock\Days())
                ->add($db)
               ;

        $t = $dayTab1->getTab();
        $labels = array();
        $values = array();
        foreach($t as $el) {
            $labels[] = $el[0];
            $values[] = $el[1];
        }
        \array_shift($labels);
        \array_shift($values);
        var_dump($values);

         /* Create and populate the pData object */
        $MyData = new \pData();
        $MyData->addPoints($values, "Probe 3");
        $MyData->setSerieWeight("Probe 3", 2);
        $MyData->setAxisName(0, $dataBlock);
        $MyData->addPoints($labels, "Labels");
        $MyData->setSerieDescription("Labels", "Days");
        $MyData->setAbscissa("Labels");

        /* Create the pChart object */
        $myPicture = new \pImage(1000, 500, $MyData);

        /* Draw the background */
        $Settings = array("R" => 170, "G" => 183, "B" => 87, "Dash" => 1, "DashR" => 190, "DashG" => 203, "DashB" => 107);
        $myPicture->drawFilledRectangle(0, 0, 1000, 500, $Settings);

        /* Overlay with a gradient */
        $Settings = array("StartR" => 219, "StartG" => 221, "StartB" => 231, "EndR" => 1, "EndG" => 68, "EndB" => 138, "Alpha" => 50);
        $myPicture->drawGradientArea(0, 0, 1000, 500, DIRECTION_VERTICAL, $Settings);
        $myPicture->drawGradientArea(0, 0, 1000, 20, DIRECTION_VERTICAL, array("StartR" => 0, "StartG" => 0, "StartB" => 0, "EndR" => 50, "EndG" => 50, "EndB" => 50, "Alpha" => 80));

        /* Add a border to the picture */
        $myPicture->drawRectangle(0, 0, 999, 499, array("R" => 0, "G" => 0, "B" => 0));

        /* Write the picture title */
        $myPicture->setFontProperties(array("FontName" => "fonts/Silkscreen.ttf", "FontSize" => 6));
        $myPicture->drawText(10, 13, "Chart for $dataBlock", array("R" => 255, "G" => 255, "B" => 255));

        /* Write the chart title */
        $myPicture->setFontProperties(array("FontName" => "fonts/Forgotte.ttf", "FontSize" => 11));
        $myPicture->drawText(250, 55, $dataBlock, array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

        /* Draw the scale and the 1st chart */
        $myPicture->setGraphArea(60, 60, 940, 400);
        $myPicture->drawFilledRectangle(60, 60, 940, 400, array("R" => 255, "G" => 255, "B" => 255, "Surrounding" => -200, "Alpha" => 10));
        $myPicture->drawScale(array("DrawSubTicks" => TRUE));
        $myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));
        $myPicture->setFontProperties(array("FontName" => "fonts/pf_arma_five.ttf", "FontSize" => 6));
        $myPicture->drawLineChart(array("DisplayValues" => TRUE, "DisplayColor" => DISPLAY_AUTO));
        $myPicture->setShadow(FALSE);

     

        /* Write the chart legend */
        $myPicture->drawLegend(510, 205, array("Style" => LEGEND_NOBORDER, "Mode" => LEGEND_VERTICAL));

        /* Build the PNG file and send it to the web browser */
        $myPicture->Render("img/chart.png");

        $template->set('randNum',  \mt_rand());

        $this->invoke($template);
    }
}

?>
