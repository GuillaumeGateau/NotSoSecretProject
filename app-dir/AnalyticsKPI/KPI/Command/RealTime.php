<?php
namespace KPI\Command;

class RealTime extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        $template = new \KPI\View\Template("KPI/View/realtime.php");

        

        $this->invoke($template);
    }
}

?>
