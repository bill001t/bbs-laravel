<?php

namespace App\Core\Hook;

class HookService extends BaseHookService
{
    protected $_interface;

    public function __construct($hookKey, $interface, $srv = '')
    {
        parent::__construct($hookKey);
        $this->setSrv($srv);
        $this->_interface = $interface;
    }

    protected function _getInterfaceName()
    {
        return $this->_interface;
    }
}

?>