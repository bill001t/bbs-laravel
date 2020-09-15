<?php

namespace App\Services\usergroup\bm;

use App\Core\BaseDm;
use App\Services\usergroup\bs\PwUserGroups;
use App\Services\usergroup\bm\PwPermissionService;

class PwUserPermissionDm extends BaseDm
{

    private $gid = 0;
    private $permission = array();

    public function __construct($gid)
    {
        $gid = intval($gid);
        $this->gid = $gid;
    }

    public function getGid()
    {
        return $this->gid;
    }

    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * 返回格式化数组,For Dao
     */
    public function getData()
    {
        $data = array();
        if (!$this->gid || !$this->permission) return $data;

        $config = app(PwPermissionService::class)->getPermissionConfig();
        foreach ($this->permission as $k => $v) {
            $vtype = 'string';
            if (is_array($v)) {
                $vtype = 'array';
                $v = serialize($v);
            }
            $data[] = array($this->gid, $k, $config[$k][1], $v, $vtype);
        }
        return $data;
    }

    public function setPermission($key, $value)
    {
        $method = 'set' . ucfirst($key);
        if (method_exists($this, $method)) {//自定义型
            return $this->{$method}($value);
        }
        $this->permission[$key] = $value;
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeAdd()
     */
    protected function _beforeAdd()
    {
        return true;
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeUpdate()
     */
    protected function _beforeUpdate()
    {
        return true;
    }
}