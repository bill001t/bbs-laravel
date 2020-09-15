<?php

namespace App\Services\Api\school\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;

class SchoolDm extends BaseDm
{

    private $schoolid = 0;

    /**
     * 设置学校ID
     *
     * @param int $schoolid
     * @return WindidSchoolDm
     */
    public function setSchoolid($schoolid)
    {
        $this->schoolid = intval($schoolid);
        return $this;
    }

    /**
     * 获得学校ID
     *
     * @return int
     */
    public function getSchoolid()
    {
        return $this->schoolid;
    }

    /**
     * 学校名称
     *
     * @param string $name
     * @return WindidSchoolDm
     */
    public function setName($name)
    {
        $this->_data['name'] = $name;
        return $this;
    }

    /**
     * 设置首字母
     *
     * @param string $first_char
     * @return WindidSchoolDm
     */
    public function setFirstChar($first_char)
    {
        $this->_data['first_char'] = $first_char;
        return $this;
    }

    /**
     * 设置类型
     *
     * @param int $typeid
     * @return WindidSchoolDm
     */
    public function setTypeid($typeid)
    {
        $this->_data['typeid'] = intval($typeid);
        return $this;
    }

    /**
     * 设置地区
     *
     * @param int $areaid
     * @return WindidSchoolDm
     */
    public function setAreaid($areaid)
    {
        $this->_data['areaid'] = intval($areaid);
        return $this;
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeAdd()
     */
    protected function _beforeAdd()
    {
        if (!isset($this->_data['name']) || !$this->_data['name']) return new ErrorBag(ErrorBag::SCHOOL_NAME_EMPTY);
        if (!isset($this->_data['areaid']) || $this->_data['areaid'] < 1) return new ErrorBag(ErrorBag::SCHOOL_AREAID_EMPTY);
        if (!isset($this->_data['typeid'])) return new ErrorBag(ErrorBag::SCHOOL_TYPEID_EMPTY);
        return true;
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeUpdate()
     */
    protected function _beforeUpdate()
    {
        if ($this->schoolid < 1) return new ErrorBag(ErrorBag::FAIL);
        unset($this->_data['typeid']);
        return true;
    }
}