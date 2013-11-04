<?php
namespace KPI\View\Helper;

class Helper {

    function __construct() {
    }

    function secho($name, $alt="") {
        if(isset($name)) {
            echo $name;
        }
        else {
            echo $alt;
        }
    }

}
?>
