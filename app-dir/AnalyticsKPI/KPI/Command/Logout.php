<?php
namespace KPI\Command;

class Logout extends Command {
    function doExecute(\KPI\Controller\Request $request) {
       
        // If user already authorized, redirect to default page
        \KPI\Base\SessionRegistry::setSession('authorized','no');
        $cmd = new DefaultCommand();
        $cmd->execute($request);

    }
}

?>
