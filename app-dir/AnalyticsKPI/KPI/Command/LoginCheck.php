<?php
namespace KPI\Command;

class LoginCheck extends Command {
    function doExecute(\KPI\Controller\Request $request) {

        // If user already authorized, redirect to default page
        if(\KPI\Base\SessionRegistry::getSession('authorized') === "yes") {
            $cmd = new DefaultCommand();
            $cmd->execute($request);
        }

        // Get expected values
        $appLogin = \KPI\Base\ApplicationConfig::getValue('login');
        $appPass = \KPI\Base\ApplicationConfig::getValue('password');

        // Get passed values
        $ul = $request->getProperty('login');
        $up = $request->getProperty('pwd');

        if($ul === $appLogin
                && $up === $appPass) {
            // login/password OK
            \KPI\Base\SessionRegistry::setSession('authorized', 'yes');
            $cmd = new DefaultCommand();
            $cmd->execute($request);
        }
        else {
            // Wrong login/password
            \KPI\Base\SessionRegistry::setSession('authorized', 'no');
            $template = new \KPI\View\Template("KPI/View/login.php");

            $template->set('feedback', "Invalid credentials");

            $this->invoke($template);
        }
        

        $template = new \KPI\View\Template("KPI/View/login.php");
        $this->invoke($template);
    }
}

?>
