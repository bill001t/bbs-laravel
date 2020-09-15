<?php

namespace App\Services\Api\area\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;

class AreaDm extends BaseDm
{

    public $areaid;

    /**
     * 设置地区ID
     *
     * @param int $areaid
     * @return WindidAreaDm
     */
    public function setAreaid($areaid)
    {
        $this->areaid = intval($areaid);
        return $this;
    }

    /**
     * 设置地区名字
     *
     * @param string $name
     * @return WindidAreaDm
     */
    public function setName($name)
    {
        $this->_data['name'] = trim($name);
        return $this;
    }

    /**
     * 设置上级地区ID
     *
     * @param int $parentid
     * @return WindidAreaDm
     */
    public function setParentid($parentid)
    {
        $this->_data['parentid'] = intval($parentid);
        return $this;
    }

    /**
     * 路径-省-市-区
     *
     * @param string $joinName
     * @return WindidAreaDm
     */
    public function setJoinname($joinName)
    {
        $this->_data['joinname'] = $joinName;
        return $this;
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeAdd()
     */
    protected function _beforeAdd()
    {
        if (!isset($this->_data['name']) || !$this->_data['name']) return new ErrorBag(ErrorBag::FAIL);
        $_tmp = str_replace(array('&', '"', "'", '<', '>', '\\', '/'), '', $this->_data['name']);
        if ($_tmp != $this->_data['name']) return new ErrorBag(ErrorBag::FAIL);
        return true;
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeUpdate()
     */
    protected function _beforeUpdate()
    {
        if (!$this->areaid) return new ErrorBag(ErrorBag::FAIL);
        if (!isset($this->_data['name']) || !$this->_data['name']) return new ErrorBag(-2);
        $_tmp = str_replace(array('&', '"', "'", '<', '>', '\\', '/'), '', $this->_data['name']);
        if ($_tmp != $this->_data['name']) return new ErrorBag(ErrorBag::FAIL);
        return true;
    }
}