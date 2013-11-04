<?php
namespace KPI\Command;

class CommandResolver {

    private static $base_cmd;
    private static $default_cmd;

    function __construct() {
        if(!self::$base_cmd) {
            self::$base_cmd = new \ReflectionClass('\KPI\Command\Command');
            self::$default_cmd = new DefaultCommand();
        }
    }

    function getCommand(\KPI\Controller\Request $request) {

        $url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

        // get command name (take out query string if any)
        $command = !empty($url[0]) ? preg_replace('/\?.*/', '', array_shift($url)) : 'DefaultCommand';
        
        // get arguments
        $args = !empty($url[0]) ? $url : array();

        $DS = \DIRECTORY_SEPARATOR;

        $command = \str_replace(array('.',$DS,' '),"", $command);
        $filepath = "KPI{$DS}Command{$DS}{$command}.php";
        $classname = "\\KPI\\Command\\{$command}";

        // If user not logged and not trying to log in, redirect to login page
        if($command!='Login' && $command!='LoginCheck' && $command!='GetRealTime' && $command!='RealTimeSummary'
                && \KPI\Base\SessionRegistry::getSession('authorized') != 'yes') {
            return new LoginRedirect();
        }

        $fg = new \KPI\Base\FileGoodies();
        if($fg->realPath($filepath)) {
            $cmd_class = new \ReflectionClass($classname);
            if($cmd_class->isSubclassOf(self::$base_cmd)) {
                return $cmd_class->newInstance($args);
            }
            else {
                $request->addFeedback("Command '$command' is not a command.");
            }
        }
        $request->addFeedback("Command '$command' not found. $filepath   $classname");
        
        return clone self::$default_cmd;
    }

    function getCommandPP(\KPI\Controller\Request $request) {
        $cmd = $request->getProperty("cmd");
        $DS = \DIRECTORY_SEPARATOR;
        if(!$cmd) {
            return clone self::$default_cmd;
        }
        $cmd = \str_replace(array('.',$DS,' '),"", $cmd);
        $filepath = "pata{$DS}command{$DS}{$cmd}.php";
        $classname = "\\pata\\command\\{$cmd}";

        $fg = new \pata\base\FileGoodies();
        if($fg->realPath($filepath)) {
            $cmd_class = new \ReflectionClass($classname);
            if($cmd_class->isSubclassOf(self::$base_cmd)) {
                return $cmd_class->newInstance();
            }
            else {
                $request->addFeedback("Command '$cmd' is not a command.");
            }
        }
        $request->addFeedback("Command '$cmd' not found. $filepath   $classname");
        return clone self::$default_cmd;
    }
}

?>
