<script type="text/javascript">
	$(document).ready(function() {
		$('.RTtip').tooltip({
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

<?php
echo date("F jS, Y")." at ".date("g:i:s A")." (UTC)";

$RTt = new \KPI\View\Helper\MakeTable($RTData, "myTab RTTab", "RTSum", true, true, false, true);
echo $RTt->getHtml();

$RTt2 = new \KPI\View\Helper\MakeTable($RTData2, "myTab RTTab", "RTTab2", true, true, false, true);
echo $RTt2->getHtml();

$RTt3 = new \KPI\View\Helper\MakeTable($RTData3, "myTab RTTab", "RTTab3", true, true, false, true);
echo $RTt3->getHtml();

//array $tab, $class="", $id="", $showHeader=true, $showKeyCol=true, $RT=false
?>

