<?php

namespace App\Services\usergroup\bm;

use App\Services\usergroup\bs\PwUserGroups;
use App\Core\BaseDm;
use App\Core\ErrorBag;

class PwUserGroupDm extends BaseDm
{

    private $groupType = 'member';
    private $gid;

    public function __construct($gid = 0)
    {
        $gid = intval($gid);
        if ($gid < 1) return;
        $this->gid = $gid;
    }

    public function setGroupName($groupName)
    {
        $this->_data['name'] = $groupName;
    }

    public function setGroupImage($groupImage)
    {
        $this->_data['image'] = $groupImage;
    }

    public function setGroupPoints($points)
    {
        $points = intval($points);
        $this->_data['points'] = $points;
    }

    public function setGroupType($groupType)
    {
        $ds = $this->loadDataService();
        if (!in_array($groupType, $ds->getGroupTypes())) return false;
        $this->_data['type'] = $groupType;
    }

    public function getGroupId()
    {
        return $this->gid;
    }

    /**
     *
     * 添加用户组校验
     */
    protected function _beforeAdd()
    {
        if (!$this->_data['name']) {
            return new ErrorBag('USER:groups.info.name.empty');
        }
        return true;
    }

    protected function _beforeUpdate()
    {
        if ($this->gid < 1) {
            return new ErrorBag('USER:groups.info.gid.error');
        } else if (!$this->_data['name']) {
            return new ErrorBag('USER:groups.info.name.empty');
        }
        return true;
    }

    protected function loadDataService()
    {
        return app(PwUserGroups::class);
    }
}