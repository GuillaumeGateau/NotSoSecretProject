<?php
namespace KPI\Command;

abstract class Command {

    private $args;

    final function  __construct($args = array()) {
        $this->args = $args;
    }

    function execute(\KPI\Controller\Request $request) {
        $this->doExecute($request);
    }

    function invoke(\KPI\View\TemplateInterface $template) {
        $template->display();
        exit(0);
    }

    abstract function doExecute(\KPI\Controller\Request $request);
}
?>
