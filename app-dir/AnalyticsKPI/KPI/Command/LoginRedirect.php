<?php
namespace KPI\Command;

class LoginRedirect extends \KPI\Command\Command {
    function doExecute(\KPI\Controller\Request $request) {

        $this->invoke(new \KPI\View\Redirect("Login"));
    }
}
?>
