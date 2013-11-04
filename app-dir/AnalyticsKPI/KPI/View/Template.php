<?php
namespace KPI\View;

class Template implements TemplateInterface {
    private $file;
    private $vars = array();

    public function __construct($file) {
        $this->file = $file;
        $this->set('H', new Helper\Helper());
    }

    public function set($varName, $value) {
        $this->vars[$varName] = $value;
    }

    public function asHtml() {
        extract($this->vars);
        ob_start();
        include $this->file;
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function display() {
        echo $this->asHtml();
    }
}

?>
