<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Smartdate KPI - Details</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/styles.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.tooltip.css" />
        <script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.tooltip.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                // Start with rows hidden
                $("#creditPlans").find("tr:gt(1)").toggle();
                
                $("#creditPlansToggle").click( function() {
                    $("#creditPlans").find("tr:gt(1)").toggle();
                });

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


        <div class="box yellow-box">
            <div class="content">
                <h3>Credits</h3>
                <?php
                $t1 = new \KPI\View\Helper\MakeTable($creditPlansData, "myTab prodTab", "creditPlans", true, true, true);
                echo $t1->getHtml();
                ?>
                <button class="myButton" id="creditPlansToggle" href="javascript:void(0)">Toggle days</button>
            </div>
            <div class="bot"></div>
        </div>

        

    </body>
</html>
