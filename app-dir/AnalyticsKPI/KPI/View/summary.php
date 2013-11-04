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
        
        <script type="text/javascript" src="js/osx.js"></script>
        <script type="text/javascript" src="js/jquery.simplemodal.js"></script>
        <link rel="stylesheet" type="text/css" href="css/osx.css" />
        

        <link href="css/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
        <script src="js/facebox.js" type="text/javascript"></script>
        
        <script type="text/javascript">
            $(document).ready(function() {
                // Start with rows hidden
                $("#mark1").find("tr:gt(1)").toggle();
                $("#mark2").find("tr:gt(1)").toggle();
                $("#mark2b").find("tr:gt(1)").toggle();
                $("#mark2c").find("tr:gt(1)").toggle();
                $("#prod1").find("tr:gt(1)").toggle();
                $("#prod2").find("tr:gt(1)").toggle();
                $("#prod3").find("tr:gt(1)").toggle();
                $("#prod4").find("tr:gt(1)").toggle();
                $("#prod5").find("tr:gt(1)").toggle();
                $("#prod6").find("tr:gt(1)").toggle();
                
                $("#daysToggle1").click( function() {
                    $("#mark1").find("tr:gt(1)").toggle();
                });
                $("#daysToggle2").click( function() {
                    $("#mark2").find("tr:gt(1)").toggle();
                });
                $("#daysToggle2b").click( function() {
                    $("#mark2b").find("tr:gt(1)").toggle();
                });
                $("#daysToggle2c").click( function() {
                    $("#mark2c").find("tr:gt(1)").toggle();
                });
                $("#daysToggle4").click( function() {
                    $("#prod1").find("tr:gt(1)").toggle();
                });
                $("#daysToggle5").click( function() {
                    $("#prod2").find("tr:gt(1)").toggle();
                });
                $("#daysToggle6").click( function() {
                    $("#prod3").find("tr:gt(1)").toggle();
                });
                $("#daysToggle7").click( function() {
                    $("#prod4").find("tr:gt(1)").toggle();
                });
                $("#daysToggle8").click( function() {
                    $("#prod5").find("tr:gt(1)").toggle();
                });
                $("#daysToggle9").click( function() {
                    $("#prod6").find("tr:gt(1)").toggle();
                });
                $("#daysToggle10").click( function() {
                    $("#prod7").find("tr:gt(1)").toggle();
                });

                $('#rangePick').datepick({rangeSelect: true, monthsToShow: 2, showTrigger: '#calImg', dateFormat: 'yyyy/mm/dd'});

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
                
                $('.note_img').tooltip({
                    track: true, 
                    delay: 0,
                    showURL: false,
                    showBody: " - ",
                    extraClass: "notes",
                    fixPNG: true,
                    opacity: 0.95,
                    right: 50,
                    top: 0
                });

                setInterval(function() {
                    $('#realtime').load('RealTimeSummary');
                }, 120000);
                $('#realtime').load('RealTimeSummary');
                

                $('a[rel*=facebox]').facebox(); 
                
            });
        </script>


    </head>
    <body>

        <?php include("KPI/View/Elements/banner.php"); ?>
        <?php include("KPI/View/Elements/menu.php"); ?>


        <br style="clear:both" />

        <div class="box blue-box">
            <div class="content">
                <h3>Real-time data</h3>
                <div id="realtime">
                     <div class="loading">&nbsp;</div>
                </div>
            </div>
            <div class="bot"></div>
        </div>

        <br/>
        <form class="filter" action="Summary" method="GET">
            Show following for :
            <?php $countryFilter = isset($countryFilter) ? $countryFilter : "all" ?>
            <select name="countryFilter" onchange="this.form.submit()">
                <option value="all" <?php echo $countryFilter=="all" ? "selected" : ""; ?> >All countries</option>
                <option value="fr" <?php echo $countryFilter=="fr" ? "selected" : ""; ?>>France</option>
                <option value="be" <?php echo $countryFilter=="be" ? "selected" : ""; ?>>Belgium</option>
                <option value="es" <?php echo $countryFilter=="es" ? "selected" : ""; ?>>Spain</option>
                <option value="it" <?php echo $countryFilter=="it" ? "selected" : ""; ?>>Italy</option>
                <option value="us" <?php echo $countryFilter=="us" ? "selected" : ""; ?>>United States</option>
                <option value="nl" <?php echo $countryFilter=="nl" ? "selected" : ""; ?>>Netherlands</option>
            </select>
            Date range: <input id="rangePick" name="rangePick" type="text" size="23" value="<?php echo $dateRange;?>"/>
            <input type="submit" value="Go" />
        </form>
        
        <div class="box red-box">
            <div class="content">

                <h3>Marketing indicators</h3>
                <div class="separator">
                <?php
                $t1 = new \KPI\View\Helper\MakeTable($dayData1, "myTab markTab", "mark1", true, true, true);
                echo $t1->getHtml(); ?>
                <button class="myButton" id="daysToggle1" href="javascript:void(0)">Toggle days</button>
                <?php
                $t2 = new \KPI\View\Helper\MakeTable($dayData2, "myTab markTab", "mark2", true, true, true);
                echo $t2->getHtml(); ?>
                <button class="myButton" id="daysToggle2" href="javascript:void(0)">Toggle days</button>
                </div>
            </div>
            <div class="bot"></div>
        </div>
        
        <div class="box yellow-box">
            <div class="content">
                   <h3>Payment providers</h3>
                <div class="separator">
                <?php
                $t2b = new \KPI\View\Helper\MakeTable($dayData2b, "myTab payTab", "mark2b", true, true, true);
                echo $t2b->getHtml(); ?>
                <button class="myButton" id="daysToggle2b" href="javascript:void(0)">Toggle days</button>
                <?php
                $t2b = new \KPI\View\Helper\MakeTable($dayData2c, "myTab payTab", "mark2c", true, true, true);
                echo $t2b->getHtml(); ?>
                <button class="myButton" id="daysToggle2c" href="javascript:void(0)">Toggle days</button>
                </div>
            </div>
            <div class="bot"></div>
        </div>                
                
                
        
        <div class="box green-box">
            <div class="content">
                <h3>Product related</h3>
                <div class="separator">
                <?php
                $t3 = new \KPI\View\Helper\MakeTable($dayData4, "myTab prodTab", "prod1", true, true, true);
                echo $t3->getHtml();
                ?>
                <button class="myButton" id="daysToggle4" href="javascript:void(0)">Toggle days</button>
                <?php
                $t4 = new \KPI\View\Helper\MakeTable($dayData5, "myTab prodTab", "prod2", true, true, true);
                echo $t4->getHtml();
                ?>
                <button class="myButton" id="daysToggle5" href="javascript:void(0)">Toggle days</button>
                <?php
                $t5 = new \KPI\View\Helper\MakeTable($dayData6, "myTab prodTab", "prod3", true, true, true);
                echo $t5->getHtml();
                ?>
                <button class="myButton" id="daysToggle6" href="javascript:void(0)">Toggle days</button>
                <?php
                $t6 = new \KPI\View\Helper\MakeTable($dayData7, "myTab prodTab", "prod4", true, true, true);
                echo $t6->getHtml();
                ?>
                <button class="myButton" id="daysToggle7" href="javascript:void(0)">Toggle days</button>
                <?php
                $t7 = new \KPI\View\Helper\MakeTable($dayData8, "myTab prodTab", "prod5", true, true, true);
                echo $t7->getHtml();
                ?>
                <button class="myButton" id="daysToggle8" href="javascript:void(0)">Toggle days</button>
                <?php
                $t8 = new \KPI\View\Helper\MakeTable($dayData9, "myTab prodTab", "prod6", true, true, true);
                echo $t8->getHtml();
                ?>
                <button class="myButton" id="daysToggle9" href="javascript:void(0)">Toggle days</button>
                <?php
                $t9 = new \KPI\View\Helper\MakeTable($dayData10, "myTab prodTab", "prod7", true, true, true);
                echo $t9->getHtml();
                ?>
                <button class="myButton" id="daysToggle10" href="javascript:void(0)">Toggle days</button>
                </div>
            </div>
            <div class="bot"></div>
        </div>
        
        <!-- modal content -->
        <div id="osx-modal-content">
            <div id="osx-modal-title">Add/Edit Note</div>
            <div class="close"><a href="#" class="simplemodal-close">x</a></div>
            <div id="osx-modal-data">
                
                 <form class="fnote" action="Summary" method="POST">
                
                    <p style="float: left;"><input id="nDate" name="nDate" type="text" size="24" disabled="disabled"/></p>
                    <div style="margin-left: 465px;"><button class="submit_note">Submit</button><button class="simplemodal-close">Close</button></div>
                    <p><textarea id="nNote" name="nNote" type="text" cols="80" rows="20" style="resize: none;"></textarea></p>
                    <input id="nKey" name="nKey" type="hidden" />
                    
                    

                </form>
            </div>
        </div>
    </div>
    <!-- /modal content -->

    </body>
</html>
