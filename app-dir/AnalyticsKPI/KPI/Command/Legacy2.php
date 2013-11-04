<?php
namespace KPI\Command;

class Legacy2 extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        $template = new \KPI\View\Template("KPI/View/legacy2.php");

        $this->invoke($template);
    }
}

?>
