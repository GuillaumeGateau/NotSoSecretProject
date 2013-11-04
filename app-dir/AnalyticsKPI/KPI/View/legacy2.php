<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Smartdate KPI - Summary</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/styles.css" />
        <script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function()
            {
                setInterval(function() {
                    $('#realtime').load('RealTime');
                }, 10000);
                $('#realtime').load('RealTime');
            });
        </script>
    </head>
    <body>

        <?php include("KPI/View/Elements/banner.php"); ?>
        <?php include("KPI/View/Elements/menu.php"); ?>

        <div class="box blue-box">
            <div class="content">
                <h3>Real-time data</h3>
                <div id="realtime">&nbsp;</div>
            </div>
            <div class="bot"></div>
        </div>

    </body>
</html>
