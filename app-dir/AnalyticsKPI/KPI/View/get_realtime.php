<?php
echo date("F jS, Y")." at ".date("g:i:s A")." (UTC)";
echo "<h3>Basic indicators<h3/>";
$RTt = new \KPI\View\Helper\MakeTable($RTData, "myTab RTTab", "RTSum");
echo $RTt->getHtml();

echo "<h3>Payment provider breakdown<h3/>";
$RTt2 = new \KPI\View\Helper\MakeTable($RTData2, "myTab RTTab", "RTPSP");
echo $RTt2->getHtml();

echo "<h3>Current top countries<h3/>";
$RTt3 = new \KPI\View\Helper\MakeTable($RTData3, "myTab RTTab", "RTCountries");
echo $RTt3->getHtml();

echo "<h3>Current top sources<h3/>";
$RTt3 = new \KPI\View\Helper\MakeTable($RTData4, "myTab RTTab", "RTSources");
echo $RTt3->getHtml();
?>
