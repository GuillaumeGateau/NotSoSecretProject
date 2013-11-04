<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Smartdate KPI - Summary</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/styles.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.datepick.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.tooltip.css" />
        <script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.datepick.min.js"></script>
        <script type="text/javascript" src="js/jquery.tooltip.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                // Start with rows hidden
                $("#mark1").find("tr:gt(5)").toggle();
                $("#mark2").find("tr:gt(10)").toggle();
                $("#prod1").find("tr:gt(1)").toggle();
                $("#prod2").find("tr:gt(1)").toggle();
                
                $("#daysToggle1").click( function() {
                    $("#mark1").find("tr:gt(5)").toggle();
                });
                $("#daysToggle2").click( function() {
                    $("#mark2").find("tr:gt(10)").toggle();
                });
                $("#daysToggle4").click( function() {
                    $("#prod1").find("tr:gt(1)").toggle();
                });
                $("#daysToggle5").click( function() {
                    $("#prod2").find("tr:gt(1)").toggle();
                });

                $('#rangePick').datepick({rangeSelect: true, monthsToShow: 2, showTrigger: '#calImg', dateFormat: 'yyyy/mm/dd'});

                setInterval(function() {
                    $('#realtime').load('RealTimeSummary');
                }, 120000);
                $('#realtime').load('RealTimeSummary');

                $('.ttip').tooltip({
                    track: true,
                    delay: 0,
                    showURL: false,
                    showBody: " - ",
                    extraClass: "pretty",
                    fixPNG: true,
                    opacity: 0.95,
                    left: -120
                });

                
            });
        </script>
    </head>
    <body>

        <?php include("KPI/View/Elements/banner.php"); ?>
        <?php include("KPI/View/Elements/menu.php"); ?>


        <br style="clear:both" />

        

        <br/>

        <form class="filter" action="Marketing" method="GET">
            <?php $countryFilter = isset($countryFilter) ? $countryFilter : "all" ?>
            Source: <input id="source" name="source" type="text" size="23" value="<?php echo $source;?>"/>
            Medium: <input id="medium" name="medium" type="text" size="23" value="<?php echo $medium;?>"/>           
            Date range: <input id="rangePick" name="rangePick" type="text" size="23" value="<?php echo $dateRange;?>"/>
            <input type="submit" value="Go" />
        </form>

        <div class="box red-box">
            <div class="content">
                <p>
                    Total registrations for that period: <?php echo $regsTotal; ?> <br/>
                    Untracked registrations: <?php echo $regsUntracked; ?> <br/>
                    Data coverage (tracked/total): <?php echo $regsTotal!=0 ? round(($regsTotal-$regsUntracked)*100/$regsTotal,2)."%" : "N/A"; ?> 
                </p>
                <?php if($source!='' || $medium!='') {?>
                <br/>
                <h2>Source: <?php echo $source; ?> - <?php echo $medium; ?></h2>
                <p>
                    Matching registrations: <?php echo $regsMatching; ?> <br/>
                    Disabled registrations: <?php echo $regsMatchingDisabled; ?> <br/>
                </p>

                <?php
                $t3 = new \KPI\View\Helper\MakeTable($dayData3, "myTab markTab", "markPerAge");
                echo $t3->getHtml(); ?>

                <h3>Per country</h3>
                <?php
                $t1 = new \KPI\View\Helper\MakeTable($dayData1, "myTab markTab", "mark1");
                echo $t1->getHtml(); ?>
                <button class="myButton" id="daysToggle1" href="javascript:void(0)">Toggle countries...</button>
      
                <h3>Per campaign</h3>
                <?php
                $t2 = new \KPI\View\Helper\MakeTable($dayData2, "myTab markTab", "mark2");
                echo $t2->getHtml(); ?>
                <button class="myButton" id="daysToggle2" href="javascript:void(0)">Toggle campaign...</button>
                <?php } ?>
            </div>
            <div class="bot"></div>
        </div>
       
    </body>
</html>
