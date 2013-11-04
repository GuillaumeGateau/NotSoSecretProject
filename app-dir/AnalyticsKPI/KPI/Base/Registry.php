<?php
namespace KPI\Base;

abstract class Registry {
    abstract protected function set($key,$value);
    abstract protected function get($key);
}
?>
