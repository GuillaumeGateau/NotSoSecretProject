<?php
namespace KPI\Command;

class Distributions extends Command {
    
    function doExecute(\KPI\Controller\Request $request) {

        $template = new \KPI\View\Template("KPI/View/distributions.php");

        $this->invoke($template);

    }        	
    
}

?>
