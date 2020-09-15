<?php

namespace App\Services\Api;

use App\Services\school\bm\SchoolService;
use App\Services\school\dm\SchoolDm;
use App\Services\school\vo\SchoolSo;
use App\Core\Utility;

class SchoolApi
{

    public function getSchool($id)
    {
        return $this->_getSchoolDs()->getSchool($id);
    }

    public function fetchSchool($ids)
    {
        return $this->_getSchoolDs()->fetchSchool($ids);
    }

    public function getSchoolByAreaidAndTypeid($areaid, $typeid)
    {
        return $this->_getSchoolDs()->getSchoolByAreaidAndTypeid($areaid, $typeid);
    }

    public function searchSchool(SchoolSo $schoolSo, $limit = 10, $start = 0)
    {
        return $this->_getSchoolDs()->searchSchool($schoolSo, $limit, $start);
    }

    public function searchSchoolData(SchoolSo $searchSo, $limit = 10, $start = 0)
    {
        return $this->_getSchoolService()->searchSchool($searchSo, $limit, $start);
    }

    public function getFirstChar($name)
    {
        return $this->_getSchoolService()->getFirstChar($name);
    }

    public function addSchool(SchoolDm $dm)
    {
        $result = $this->_getSchoolDs()->addSchool($dm);
        return Utility::result($result);
    }

    public function batchAddSchool($schoolDms)
    {
        $result = $this->_getSchoolDs()->batchAddSchool($schoolDms);
        return Utility::result($result);
    }

    public function updateSchool(SchoolDm $schooldm)
    {
        $result = $this->_getSchoolDs()->updateSchool($schooldm);
        return Utility::result($result);
    }

    public function deleteSchool($schoolid)
    {
        $result = $this->_getSchoolDs()->deleteSchool($schoolid);
        return Utility::result($result);
    }

    private function _getSchoolDs()
    {
        return app(School::class);
    }

    private function _getSchoolService()
    {
        return app(SchoolService::class);
    }
}

?>