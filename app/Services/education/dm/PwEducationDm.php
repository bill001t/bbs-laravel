<?php

namespace App\Services\education\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;
use App\Core\EducationHelper;

class PwEducationDm extends BaseDm
{

    /**
     * 设置用户ID
     *
     * @param int $uid
     * @return PwEducationDm
     */
    public function setUid($uid)
    {
        $this->_data['uid'] = intval($uid);
        return $this;
    }

    /**
     * 设置教育单位名字
     *
     * @param string $school
     * @return PwEducationDm
     */
    public function setSchoolid($school)
    {
        $this->_data['schoolid'] = intval($school);
        return $this;
    }

    /**
     * 设置学历
     *
     * @param string $degree
     * @return PwEducationDm
     */
    public function setDegree($degree)
    {
        $this->_data['degree'] = intval($degree);
        return $this;
    }

    /**
     * 设置开始时间
     *
     * @param int $year
     * @return PwEducationDm
     */
    public function setStartTime($year)
    {
        $this->_data['start_time'] = intval($year);
        return $this;
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeAdd()
     */
    protected function _beforeAdd()
    {
        return $this->check();
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeUpdate()
     */
    protected function _beforeUpdate()
    {
        return $this->check();
    }

    /**
     * 检查数据
     *
     * @return ErrorBag
     */
    protected function check()
    {
        if (!isset($this->_data['uid'])) return new ErrorBag('USER:education.illegal.request');
        if (!isset($this->_data['schoolid']) || !$this->_data['schoolid']) return new ErrorBag('USER:education.update.school.require');
        if (!isset($this->_data['start_time']) || !$this->_data['start_time']) return new ErrorBag('USER:education.update.start_time.require');
        $this->_data['start_time'] = EducationHelper::checkEducationYear($this->_data['start_time']);
        if (!EducationHelper::checkDegree($this->_data['degree'])) return new ErrorBag('USER:education.update.degree.error');
        return true;
    }
}