<?php

namespace App\Services\work\bs;

use App\Core\ErrorBag;
use App\Services\work\dm\PwWorkDm;
use App\Services\work\ds\dao\PwWorkDao;

class PwWork
{

    /**
     * 添加工作经历
     *
     * @param PwWorkDm $dm
     * @return ErrorBag|int
     */
    public function addWorkExperience($dm)
    {
        if (!$dm instanceof PwWorkDm) return new ErrorBag('USER:work.illegal.datatype');
        if (($result = $dm->beforeAdd()) instanceof ErrorBag) return $result;
        return $this->_getDao()->add($dm->getData());
    }

    /**
     * 更新工作经历
     *
     * @param id $id
     * @param PwWorkDm $dm
     * @return ErrorBag|boolean|int
     */
    public function editWorkExperience($id, $dm)
    {
        $uid = intval($dm->getField('uid'));
        if (($id = intval($id)) < 1 || $uid < 1) return new ErrorBag('USER:work.illegal.request');
        if (!$dm instanceof PwWorkDm) return new ErrorBag('USER:work.illegal.datatype');
        if (($result = $dm->beforeUpdate()) instanceof ErrorBag) return $result;
        return $this->_getDao()->_update($id, $uid, $dm->getData());
    }

    /**
     * 删除工作经历
     *
     * @param int $id 工作经历ID
     * @param int $uid 对应用户ID
     * @return ErrorBag|int
     */
    public function deleteWorkExperience($id, $uid)
    {
        if (($id = intval($id)) < 1 || ($uid = intval($uid)) < 1) return new ErrorBag('USER:work.illegal.request');
        return $this->_getDao()->_delete($id, $uid);
    }

    /**
     * 根据ID获得用户工作经历
     *
     * @param int $id 经历ID
     * @param int $uid 用户ID
     * @return ErrorBag|array
     */
    public function getWorkExperienceById($id, $uid)
    {
        if (($id = intval($id)) < 1 || ($uid = intval($uid)) < 1) return new ErrorBag('USER:work.illegal.request');
        return $this->_getDao()->get($id, $uid);
    }

    /**
     * 根据用户ID删除用户的工作经历
     *
     * @param int $uid
     * @return ErrorBag|int
     */
    public function deleteWorkExperienceByUid($uid)
    {
        if (($uid = intval($uid)) < 1) return new ErrorBag('USER:work.illegal.uid');
        return $this->_getDao()->deleteByUid($uid);
    }

    /**
     * 根据用户ID获得用户工作经历列表
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
     * 根据用户ID统计该用户拥有的工作经历
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
     * 获得工作经历相关dao
     *
     * @return PwWorkDao
     */
    private function _getDao()
    {
        return app(PwWorkDao::class);
    }
}