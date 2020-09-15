<?php

namespace App\Services\education\dm;

use App\Core\ErrorBag;
use App\Services\education\ds\dao\PwEducationDao;

class PwEducation
{

    /**
     * 添加教育经历
     *
     * @param PwEducationDm $dm
     * @return ErrorBag|int
     */
    public function addEducation($dm)
    {
        if (!$dm instanceof PwEducationDm) return new ErrorBag('USER:education.illegal.datatype');
        if (($result = $dm->beforeAdd()) instanceof ErrorBag) return $result;
        return $this->_getDao()->add($dm->getData());
    }

    /**
     * 更新教育经历
     *
     * @param id $id
     * @param PwEducationDm $dm
     * @return ErrorBag|boolean|int
     */
    public function editEducation($id, $dm)
    {
        $uid = intval($dm->getField('uid'));
        if (($id = intval($id)) < 1 || $uid < 1) return new ErrorBag('USER:education.illegal.request');
        if (!$dm instanceof PwEducationDm) return new ErrorBag('USER:education.illegal.datatype');
        if (($result = $dm->beforeUpdate()) instanceof ErrorBag) return $result;
        $data = $dm->getData();
        unset($data['uid']);
        return $this->_getDao()->_update($id, $uid, $data);
    }

    /**
     * 删除教育经历
     *
     * @param int $id 教育经历ID
     * @param int $uid 对应用户ID
     * @return ErrorBag|int
     */
    public function deleteEducation($id, $uid)
    {
        if (($id = intval($id)) < 1 || ($uid = intval($uid)) < 1) return new ErrorBag('USER:education.illegal.request');
        return $this->_getDao()->_delete($id, $uid);
    }

    /**
     * 根据ID获得用户教育经历
     *
     * @param int $id 经历ID
     * @param int $uid 用户ID
     * @return ErrorBag|array
     */
    public function getEducationById($id, $uid)
    {
        if (($id = intval($id)) < 1 || ($uid = intval($uid)) < 1) return new ErrorBag('USER:education.illegal.request');
        return $this->_getDao()->get($id, $uid);
    }

    /**
     * 根据用户ID删除用户的教育经历
     *
     * @param int $uid
     * @return ErrorBag|int
     */
    public function deleteEducationByUid($uid)
    {
        if (($uid = intval($uid)) < 1) return new ErrorBag('USER:education.illegal.uid');
        return $this->_getDao()->deleteByUid($uid);
    }

    /**
     * 根据用户ID获得用户教育经历列表
     *
     * @param int $uid
     * @param int $number 返回条数，默认为10
     * @param int $start 开始搜索的位置
     * @return array
     */
    public function getByUid($uid, $number = 10, $start = 0)
    {
        if (($uid = intval($uid)) < 1) return array();
        return $this->_getDao()->getByUid($uid, $number, $start);
    }

    /**
     * 根据用户ID统计该用户拥有的教育经历
     *
     * @param int $uid
     * @return int
     */
    public function countByUid($uid)
    {
        if (($uid = intval($uid)) < 1) return 0;
        return $this->_getDao()->countByUid($uid);
    }

    /**
     * 获得教育经历相关dao
     *
     * @return PwEducationDao
     */
    private function _getDao()
    {
        return app(PwEducationDao::class);
    }
}