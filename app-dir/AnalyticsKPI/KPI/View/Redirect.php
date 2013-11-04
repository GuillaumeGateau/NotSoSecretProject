<?php
namespace KPI\View;

class Redirect implements TemplateInterface {
    private $url;

    public function __construct($url) {
        $this->url = $url;
    }

    public function display() {
        \header("Location: ".$this->url);
        exit;
    }
}
?>
