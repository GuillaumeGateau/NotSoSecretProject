<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Smartdate KPI - Chargebacks</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/styles.css" />
        <link rel="stylesheet" type="text/css" href="css/pagination.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.tooltip.css" />
        <script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.tooltip.min.js"></script>
        <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
        <script type="text/javascript" src="js/jquery.tablesorter.pager.js"></script>
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
                
				$("#ChargebackTab").tablesorter( {widthFixed: true, sortList: [[1,1]]} )
				.tablesorterPager({container: $("#pager")});
				;

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
                
                $(".cbdel").click(function() {  
					var cbid = $(this).attr("cbid");
  					var cbdel = confirm("Are you sure you want to delete this record from the system?");
					if (cbdel){
  		  				var dataString = '?cdel='+cbid;
				  		window.location.href = dataString;
 					}
  					return false;
  				});
  
            });
        </script>
        <script type="text/javascript" language="JavaScript">
		function ActionDeterminator(){
			if (
				$('#cbPayProv').val() == 'Payment Provider' &&
				$('#cbDate').val() == 'Chargeback Date' &&
				$('#purchDate').val() == 'Purchase Date' &&
				$('#cardNbr').val() == 'Card Number' &&
				$('#userid').val() == 'User Id' &&
				$('#cbReason').val() == 'Chargeback Reason' &&
				$('#cardType').val() == 'Card Type' &&
				$('#cbInEuros').val() == 'Chargeback Amount (in €)' &&
				$('#cbDefended').val() == 'Chargeback Defended' &&
				$('#cbWon').val() == 'Chargeback Won' &&
				$('#regDate').val() == 'Date of Registration' &&
				$('#cardCountry').val() == 'Card Issiung Country'
				){
				alert("All fields are required. Please, complete all required values and try again.");
				return false;
			} else txtfld4
		}
		</script>
    </head>
    <body>

        <?php include("KPI/View/Elements/banner.php"); ?>
        <?php include("KPI/View/Elements/menu.php"); ?>


        <div class="box yellow-box">
            <div class="content">
                <h3>Chargebacks</h3>
                <p></p>
                <form class="fchargeback" action="" method="POST">
                	
                	<div class="fields_4">
                	<input class="txtfld4" id="cbPayProv" name="cbPayProv" type="text" size="24" value="Payment Provider"
                		onfocus="if(this.value == 'Payment Provider') this.value = '';" onblur="if(this.value == '') this.value = 'Payment Provider';"/>
                	<input class="txtfld4" id="cbDate" name="cbDate" type="text" size="24" value="Chargeback Date"
                		onfocus="if(this.value == 'Chargeback Date') this.value = '';" onblur="if(this.value == '') this.value = 'Chargeback Date';"/>
                	<input class="txtfld4" id="purchDate" name="purchDate" type="text" size="24" value="Purchase Date"
                		onfocus="if(this.value == 'Purchase Date') this.value = '';" onblur="if(this.value == '') this.value = 'Purchase Date';"/>
                	<input class="txtfld4" id="cardNbr" name="cardNbr" type="text" size="24" value="Card Number"
                		onfocus="if(this.value == 'Card Number') this.value = '';" onblur="if(this.value == '') this.value = 'Card Number';"/>
                	</div>
                	
                	<div class="fields_4">
                	<input class="txtfld4" id="userid" name="userid" type="text" size="24" value="User Id"
                		onfocus="if(this.value == 'User Id') this.value = '';" onblur="if(this.value == '') this.value = 'User Id';"/>
                	<input class="txtfld4" id="cbReason" name="cbReason" type="text" size="24" value="Chargeback Reason"
                		onfocus="if(this.value == 'Chargeback Reason') this.value = '';" onblur="if(this.value == '') this.value = 'Chargeback Reason';"/>
                	<input class="txtfld4" id="cardType" name="cardType" type="text" size="24" value="Card Type"
                		onfocus="if(this.value == 'Card Type') this.value = '';" onblur="if(this.value == '') this.value = 'Card Type';"/>
                	<input class="txtfld4" id="cbInEuros" name="cbInEuros" type="text" size="24" value="Chargeback Amount (in €)"
                		onfocus="if(this.value == 'Chargeback Amount (in €)') this.value = '';" onblur="if(this.value == '') this.value = 'Chargeback Amount (in €)';"/>
                	</div>
                	
                	<div class="fields_5">
                	<input class="txtfld5" id="cbDefended" name="cbDefended" type="text" size="24" value="Chargeback Defended"
                		onfocus="if(this.value == 'Chargeback Defended') this.value = '';" onblur="if(this.value == '') this.value = 'Chargeback Defended';"/>
                	<input class="txtfld5" id="cbWon" name="cbWon" type="text" size="24" value="Chargeback Won"
                		onfocus="if(this.value == 'Chargeback Won') this.value = '';" onblur="if(this.value == '') this.value = 'Chargeback Won';"/>
                	<input class="txtfld5" id="agentName" name="agentName" type="text" size="24" value="Agent Name"
                		onfocus="if(this.value == 'Agent Name') this.value = '';" onblur="if(this.value == '') this.value = 'Agent Name';"/>
                	<input class="txtfld5" id="regDate" name="regDate" type="text" size="24" value="Date of Registration"
                		onfocus="if(this.value == 'Date of Registration') this.value = '';" onblur="if(this.value == '') this.value = 'Date of Registration';"/>
                	<input class="txtfld5" id="cardCountry" name="cardCountry" type="text" size="24" value="Card Issiung Country"
                		onfocus="if(this.value == 'Card Issiung Country') this.value = '';" onblur="if(this.value == '') this.value = 'Card Issiung Country';"/>
                	<input type="submit" value="Go" onClick="ActionDeterminator();"/>
                	</div>
                	
                </form>
                <p>
                <?php
                $t1 = new \KPI\View\Helper\CBMakeTable($t1Data, "myTab countryTab", "ChargebackTab");
                //$t1 = new \KPI\View\Helper\CBMakeTable($t1Data, "tablesorter", "ChargebackTab");
                echo $t1->getHtml();
                ?>
            </div>
            
            <div class="bot"></div>
        </div> 

    </body>
</html>
