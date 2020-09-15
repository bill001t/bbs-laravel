<?php

namespace App\Core\Hook;

abstract class BaseHookInjector
{

    private $callback = 'run';
    private $args = array();
    protected $bp = null;

    public function __construct($args = array())
    {
        !empty($args[0]) && $this->callback = $args[0];
        isset($args[1]) && $this->bp = $args[1];

        if (count($args) > 2) {
            unset($args[0], $args[1]);
            $this->args = $args;
        }
    }


    public function preHandle()
    {
        if (!method_exists($this, $this->callback)) return;

        $injector = call_user_func_array(array($this, $this->callback), $this->args);
        if ($injector) $this->bp->appendDo($injector);
    }

    public function postHandle()
    {
    }

}

?>