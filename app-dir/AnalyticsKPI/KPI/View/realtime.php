<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Smartdate KPI - Summary</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/styles.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.tooltip.css" />
        <script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.tooltip.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                // Start with rows hidden
                $("#mark1").find("tr:gt(1)").toggle();
                $("#mark2").find("tr:gt(1)").toggle();
                $("#prod1").find("tr:gt(1)").toggle();
                $("#prod2").find("tr:gt(1)").toggle();
                
                $("#daysToggle1").click( function() {
                    $("#mark1").find("tr:gt(1)").toggle();
                });
                $("#daysToggle2").click( function() {
                    $("#mark2").find("tr:gt(1)").toggle();
                });
                $("#daysToggle4").click( function() {
                    $("#prod1").find("tr:gt(1)").toggle();
                });
                $("#daysToggle5").click( function() {
                    $("#prod2").find("tr:gt(1)").toggle();
                });

                setInterval(function() {
                    $('#realtime').load('GetRealTime');
                }, 300000);
                $('#realtime').load('GetRealTime');

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


    </body>
</html>
