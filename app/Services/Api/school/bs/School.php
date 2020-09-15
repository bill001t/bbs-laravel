<?php

namespace App\Services\Api\school\bs;

use App\Services\Api\school\ds\dao\SchoolDao;
use App\Services\Api\school\vo\SchoolSo;
use App\Services\Api\school\dm\SchoolDm;

class School
{
    /* 小学 */
    const PRIMARY = 1;
    /* 中学 */
    const HIGN = 2;
    /* 大学 */
    const UNIVERSITY = 3;

    /**
     * 根据学校ID获得学校详细信息
     *
     * @param int $schoolid
     * @return array
     */
    public function getSchool($schoolid)
    {
        if (($schoolid = intval($schoolid)) < 1) return array();
        return $this->_getDao()->getSchool($schoolid);
    }

    /**
     * 根据学校ID批量获取学校的信息
     *
     * @param array $schoolids
     * @return array
     */
    public function fetchSchool($schoolids)
    {
        if (!$schoolids) return array();
        return $this->_getDao()->fetchSchool($schoolids);
    }

    /**
     * 根据地区ID和学校类型获得学校数据
     *
     * @param int $areaid
     * @param int $typeid
     * @return array
     */
    public function getSchoolByAreaidAndTypeid($areaid, $typeid = self::PRIMARY)
    {
        if (($areaid = intval($areaid)) < 1) return array();
        return $this->_getDao()->getSchoolByAreaidAndTypeid($areaid, $typeid);
    }

    /**
     * 添加学校
     *
     * @param SchoolDm $schooldm
     * @return int
     */
    public function addSchool(SchoolDm $schooldm)
    {
        if (true !== ($r = $schooldm->beforeAdd())) return $r;
        return $this->_getDao()->addSchool($schooldm->getData());
    }

    /**
     * 批量添加学校
     *
     * @param array $schoolDms
     * @return WindidError|int
     */
    public function batchAddSchool($schoolDms)
    {
        $data = array();
        foreach ($schoolDms as $_dm) {
            if (!$_dm instanceof SchoolDm) continue;
            if (true !== ($r = $_dm->beforeAdd())) return $r;
            $data[] = $_dm->getData();
        }
        return $this->_getDao()->batchAddSchool($data);
    }

    /**
     * 更新学校数据
     *
     * @param SchoolDm $schooldm
     * @return int
     */
    public function updateSchool(SchoolDm $schooldm)
    {
        if (true !== ($r = $schooldm->beforeUpdate())) return $r;
        return $this->_getDao()->updateSchool($schooldm->getSchoolid(), $schooldm->getData());
    }

    /**
     * 删除学校
     *
     * @param int $schoolid
     * @return int
     */
    public function deleteSchool($schoolid)
    {
        if (($schoolid = intval($schoolid)) < 1) return false;
        return $this->_getDao()->deleteSchool($schoolid);
    }

    /**
     * 批量删除学校
     *
     * @param array $schoolids
     * @return int
     */
    public function batchDeleteSchool($schoolids)
    {
        if (!$schoolids) return false;
        return $this->_getDao()->batchDeleteSchool($schoolids);
    }

    /**
     * 搜索学校
     *
     * @param SchoolSo $schoolSo
     * @return array
     */
    public function searchSchool(SchoolSo $schoolSo, $limit = 10, $start = 0)
    {
        return $this->_getDao()->searchSchool($schoolSo->getData(), $limit, $start);
    }

    /**
     * 统计搜索结果
     *
     * @param SchoolSo $schoolSo
     * @return int
     */
    public function countSearchSchool(SchoolSo $schoolSo)
    {
        return $this->_getDao()->countSearchSchool($schoolSo->getData());
    }

    /**
     * 获得学校库DAO
     *
     * @return WindidSchoolDao
     */
    private function _getDao()
    {
        return app(SchoolDao::class);
    }
}