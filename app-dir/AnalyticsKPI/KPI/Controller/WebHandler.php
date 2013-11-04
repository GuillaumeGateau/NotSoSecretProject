<?php
namespace pata\controller;

// Wraps over Controller to add dispatching to Controller, AdminController, OwnersController, etc
// requires that those controllers be in specific directories (/, /admin/, /owners/ ...)
class WebHandler {

    private $baseURI;
    private $controlOptions;
    private $uri;

    public function __construct(array $controlOptions) {
        $this->baseURI = \pata\base\ApplicationConfig::getValue('baseURI');
        $this->controlOptions = $controlOptions;
        $this->uri = $_SERVER['REQUEST_URI'];
        
    }

    public function getControlName() {
        
        foreach($this->controlOptions as $controlName) {
            // $baseURI must end with '/' (defined in ApplicationConfig)
            $beginningPath = $this->baseURI.$controlName;
            
            if(strpos($this->uri,$beginningPath)===0) {
                return $controlName;
            }
        }
        return '';
    }

    public function run() {
        $controlName = $this->getControlName();
        $controllerClassName = "\\pata\\".
            ($controlName=="" ? "" : $controlName."\\").
            "controller\\".ucfirst($controlName)."Controller";
        $controllerClassName::run();
    }


}


?>
