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


        <div class="box yellow-box">
            <div class="content">
                <h3>Subscription Pricing (Since June 14th)</h3>
                <h4>Adyen</h4>
                <table class="myTab pricingTab" id="pricingTab">
                <thead>
                	<th>France and Belgium</th>
                	<th>1 Month</th>
                	<th>3 Months</th>
                	<th>6 Months</th>
                </thead>
                <tbody>
                	<tr>
                		<td>40+ Monthly</td>
                		<td>44.99</td>
                		<td>29.99</td>
                		<td>24.99</td>
                	</tr>
                	<tr>
                		<td>40+ Total</td>
                		<td>44.99</td>
                		<td>89.97</td>
                		<td>149.94</td>
                	</tr>
                	<tr>
                		<td>ESE Monthly</td>
                		<td>29.99</td>
                		<td>19.99</td>
                		<td>14.99</td>
                	</tr>
                	<tr>
                		<td>ELSE Total</td>
                		<td>29.99</td>
                		<td>59.97</td>
                		<td>89.94</td>
                	</tr>
                </tbody>
                </table>
                
                <h4>Payment Wall</h4>
                <table class="myTab pricingTab" id="pricingTab">
                <thead>
                	<th>Pricing</th>
                	<th>1 Month</th>
                	<th>3 Months</th>
                	<th>6 Months</th>
                </thead>
                <tbody>
                	<tr>
                		<td>Spain and Italy</td>
                		<td>14.99</td>
                		<td>29.99</td>
                		<td>44.97</td>
                	</tr>
                </tbody>
                </table>
                
                <table class="myTab pricingTab" id="pricingTab">
                <thead>
                	<th>Pricing</th>
                	<th>1 Month</th>
                	<th>3 Months</th>
                	<th>6 Months</th>
                </thead>
                <tbody>
                	<tr>
                		<td>Rest of the world</td>
                		<td>14.99</td>
                		<td>22.47</td>
                		<td>29.94</td>
                	</tr>
                </tbody>
                </table>
                
                
            </div>
            <div class="bot"></div>
        </div>
        
        <div class="box yellow-box">
            <div class="content">
                <h3>Credit Pricing</h3>
                <table class="myTab pricingTab" id="pricingTab2">
				<thead>
					<th></th>
					<th>Price</th>
				</thead>
                <tbody>
                	<tr>
                		<td>1000 Credits</td>
                		<td>19.99</td>
                	</tr>
                	<tr>
                		<td>450 Credits</td>
                		<td>9.99</td>
                	</tr>
                	<tr>
                		<td>150 Credits</td>
                		<td>4.99</td>
                	</tr>
                </tbody>
                </table>                
            </div>
            <div class="bot"></div>
        </div>
        
        <div class="box yellow-box">
            <div class="content">
                <h3>Feature Costs (Credits)</h3>
                <table class="myTab pricingTab" id="pricingTab2">
				<thead>
					<th></th>
					<th>Credits</th>
				</thead>
                <tbody>
                	<tr>
                		<td>VIP Lounge</td>
                		<td>95</td>
                	</tr>
                	<tr>
                		<td>Chat Unblock</td>
                		<td>150</td>
                	</tr>
                	<tr>
                		<td>Chat Unlock</td>
                		<td>150</td>
                	</tr>
                	<tr>
                		<td>Message Unlock</td>
                		<td>150</td>
                	</tr>
                </tbody>
                </table>                
            </div>
            <div class="bot"></div>
        </div>

        

    </body>
</html>
