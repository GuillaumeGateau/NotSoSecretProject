<?php
namespace KPI\Command;

class Pricing extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        $template = new \KPI\View\Template("KPI/View/pricing.php");

        $this->invoke($template);
    }
}

?>
