<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Smartdate KPI - Monthly</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/styles.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.tooltip.css" />
        <script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.tooltip.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#daysToggle1").click( function() {
                    $("#mark1").find("tr:gt(1)").toggle();
                });
                $("#daysToggle2").click( function() {
                    $("#mark2").find("tr:gt(1)").toggle();
                });
                $("#daysToggle3").click( function() {
                    $("#prod1").find("tr:gt(1)").toggle();
                });
                $("#daysToggle4").click( function() {
                    $("#prod2").find("tr:gt(1)").toggle();
                });

                // Start with rows hidden
                $("#mark1").find("tr:gt(1)").toggle();
                $("#mark2").find("tr:gt(1)").toggle();
                $("#prod1").find("tr:gt(1)").toggle();
                $("#prod2").find("tr:gt(1)").toggle();

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

        <br/>
        
         <form class="filter" action="Monthly" method="GET">
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
        </form>


        <div class="box yellow-box">
            <div class="content">
                <h3>Until <?php echo $lastDay; ?></h3>
                <?php
                $t1 = new \KPI\View\Helper\MakeTable($t1Data, "myTab countryTab", "topCountries");
                echo $t1->getHtml();
                ?>
            </div>
            <div class="bot"></div>
        </div>

        

    </body>
</html>
