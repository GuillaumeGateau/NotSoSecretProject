<?php
namespace KPI\Command;

class Login extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        
        // If user already authorized, redirect to default page
        if(\KPI\Base\SessionRegistry::getSession('authorized') === "yes") {
            $cmd = new DefaultCommand();
            $cmd->execute($request);
        }

        $template = new \KPI\View\Template("KPI/View/login.php");
        $this->invoke($template);
    }
}

?>
