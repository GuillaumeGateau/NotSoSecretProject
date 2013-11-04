<?php
namespace KPI\Command;

class DefaultCommand extends \KPI\Command\Command {
    function doExecute(\KPI\Controller\Request $request) {

        $this->invoke(new \KPI\View\Redirect("/Summary"));
    }
}
?>
