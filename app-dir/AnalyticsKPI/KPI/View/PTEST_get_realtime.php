<?php
echo date("F jS, Y")." at ".date("g:i:s A")." (UTC)";


$RTt = new \KPI\View\Helper\MakeTable($RTData, "myTab RTTab", "RTSum");
echo $RTt->getHtml();

?>
