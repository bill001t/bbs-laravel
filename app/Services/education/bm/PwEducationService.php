<?php

namespace App\Services\education\bm;

use App\Core\EducationHelper;
use App\Services\area\bs\AreaApi;
use App\Services\school\bs\SchoolApi;

class PwEducationService
{

    /**
     * 根据用户ID获得该用户的教育经历
     *
     * @param int $uid
     * @param int $num
     * @param boolean $buildArea 是否需要查询获取学校地区的层级
     * @return array
     */
    public function getEducationByUid($uid, $num = 10, $buildArea = false)
    {
        $educations = $this->_getDs()->getByUid($uid, $num);
        if (!$educations) return array();
        $schoolids = array();
        foreach ($educations as $key => $education) {
            $educations[$key]['degreeid'] = $education['degree'];
            $educations[$key]['degree'] = EducationHelper::getDegrees($education['degree']);
            $schoolids[] = $education['schoolid'];
        }
        $schools = $this->_getSchoolDs()->fetchSchool($schoolids);
        $areaids = array();
        foreach ($educations as $key => $education) {
            $educations[$key]['school'] = isset($schools[$education['schoolid']]) ? $schools[$education['schoolid']]['name'] : '';
            $buildArea && $educations[$key]['areaid'] = $schools[$education['schoolid']]['areaid'];
            $areaids[] = $schools[$education['schoolid']]['areaid'];
        }
        if ($buildArea) {
            $areaSrv = $this->_getAreaDs();
            $areas = $areaSrv->fetchAreaRout($areaids);
            foreach ($educations as $key => $education) {
                $educations[$key]['areaid'] = $areas[$educations[$key]['areaid']];
            }
        }
        return $educations;
    }

    /**
     * 获得学校Ds
     *
     * @return WindidSchool
     */
    private function _getSchoolDs()
    {
        return app(SchoolApi::class);
    }

    private function _getAreaDs()
    {
        return app(AreaApi::class);
    }

    /**
     * 教育经历DS
     *
     * @return PwEducation
     */
    private function _getDs()
    {
        return app(PwEducation::class);
    }
}