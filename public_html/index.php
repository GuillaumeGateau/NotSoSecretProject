<?php
//ini_set('display_errors', 1);
mb_language('uni'); mb_internal_encoding('UTF-8');
date_default_timezone_set("UTC");
// Uncomment following line for LINUX server
ini_set('include_path',ini_get('include_path').':/var/www/app-kpi/current/app-dir/AnalyticsKPI');
//ini_set('include_path',ini_get('include_path').':/Applications/MAMP/htdocs/app-kpi-v2/app-dir/AnalyticsKPI');
require_once 'KPI/Base/FileGoodies.php';

function __autoload($classname) {
    if (preg_match("/\\\\/", $classname)) {
        $path = str_replace("\\", DIRECTORY_SEPARATOR, $classname);
    } 
    else {
        $path = $classname;
    }
    //require($path . '.php');
    $file = $path . '.php';


    $fg = new KPI\Base\FileGoodies();
    if (!$fg->realPath($file)) {
        //eval('class ' . $classname . '{}');
        throw new \Exception('File ' . $file . ' not found.');
    }

    require_once($file);
    unset($file);
    
    
    
    if (!class_exists($classname, FALSE) && !interface_exists($classname, FALSE)) {
        //eval('class ' . $classname . '{}');
        throw new \Exception('Class ' . $classname . ' not found.');
    }
}



//$tab = new KPI\Table\PerCountryTab();
//$tab->build();
//print_r($tab);

//$test=\KPI\base\GlobalSettings::instance();
/*
$b = new KPI\DBlock\LastDays();
$b->execute();
print_r($b->getCol());

$b = new KPI\DBlock\SalesPerCountry();
print_r($b->execute()->getCol());*/
//echo "elle";exit;


try {
    KPI\Controller\Controller::run();
} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}

/*
$perCountryTab = new KPI\Table\Table();
$perCountryTab->add(new \KPI\DBlock\TopCountries());$perCountryTab->add(new KPI\DBlock\SalesPerCountry());
$t = $perCountryTab->getTab();
//print_r($t);
$help = new KPI\View\Helper\MakeTable($t);
echo $help->getHtml();*/

?>
