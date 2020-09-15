<?php

namespace App\Services\hook\dm;

class PwHookSo
{
    protected $_data = array();

    /**
     * 设置hook名
     *
     * @param string $v
     * @return PwHookSo
     */
    public function setName($v)
    {
        $v && ($this->_data['name'] = $v);
        return $this;
    }

    /**
     * 应用名称
     *
     * @param string $v
     * @return PwHookSo
     */
    public function setAppName($v)
    {
        $v && ($this->_data['app_name'] = $v);
        return $this;
    }

    public function getData()
    {
        return $this->_data;
    }
}

?>