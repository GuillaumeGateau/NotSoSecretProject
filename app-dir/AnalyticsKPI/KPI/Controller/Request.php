<?php
namespace KPI\Controller;

class Request {
    private $properties;
    private $feedback = array();
    private $objects = array();

    function __construct() {
        $this->init();
        \KPI\Base\RequestRegistry::setRequest($this);
    }

    protected function init() {
        if(isset($_SERVER['REQUEST_METHOD'])) {
            $this->properties = $_REQUEST;
            return;
        }

        foreach ($_SERVER['argv'] as $argument) {
            list($key, $value) = explode('=', $argument);
            $this->properties[$key] = $value;
        }
    }

    function getProperty($key) {
        if(isset($this->properties[$key])) {
            return $this->properties[$key]!='' ? $this->properties[$key] : null;
        }
        return null;
    }

    function getProperties() {
        return isset($this->properties) ? $this->properties : array();
    }

    function setProperty($key,$value) {
        $this->properties[$key] = $value;
    }

    function addFeedback($msg) {
        $this->feedback[] = $msg;
    }

    function getFeedback() {
        return $this->feedback;
    }

    function getFeedbackString($separator = '\n') {
        return implode($separator,$this->feedback);
    }

    function setObject($key, $obj) {
        $this->objects[$key] = $obj;
    }

    function getObject($key) {
        if(isset($this->objects[$key])) {
            if(\is_object($this->objects[$key])) {
                return $this->objects[$key];
            }
        }
        return null;
    }
}
?>
