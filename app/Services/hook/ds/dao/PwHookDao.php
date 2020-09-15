<?php

namespace App\Services\hook\ds\dao;

use App\Services\hook\ds\relation\Hook;

class PwHookDao extends Hook
{
    public function add($fields)
    {
        return self::create($fields);
    }

    public function _update($name, $fields)
    {
        return self::where('name', $name)
            ->update($fields);
    }

    public function batchAdd($fields)
    {
        return self::create($fields);
    }

    /**
     * 根据App_id删除钩子信息
     *
     * @param string $app_id
     * @return boolean
     */
    public function delByAppId($app_id)
    {
        return self::where('app_id', $app_id)
        ->delete();
    }

    /**
     * 根据应用名称删除
     *
     * @param string $appName
     * @return Ambigous <rowCount, boolean, number>
     */
    public function delByAppName($appName)
    {
        return self::where('app_name', $appName)
            ->delete();
    }

    /**
     * 根据钩子名称删除钩子定义
     *
     * @param string $name
     * @return boolean
     */
    public function delByName($name)
    {
        return self::where('name', $name)
            ->delete();
    }

    /**
     * 根据名称批量删除hook
     *
     * @param array $names
     * @return Ambigous <rowCount, boolean, number>
     */
    public function batchDelByName($names)
    {
        return self::whereIn('name', $names)
            ->delete();

    }

    /**
     * 根据hook name 更新，返回影响行数
     *
     * @param string $name
     * @return int
     */
    public function updateByName($name, $fields)
    {
        return self::where('name', $name)
            ->update($fields);
    }

    /**
     * 根据name查找hook注册信息，返回hook数据
     *
     * @param string $appId
     * @return array
     */
    public function find($name)
    {
        return self::where('name', $name)
            ->get();
    }

    /**
     * 根据应用ID查找Hook信息
     *
     * @param int $appIds
     * @return Ambigous <multitype:, multitype:multitype: Ambigous <multitype:, multitype:unknown , mixed> >
     */
    public function findByAppId($appId)
    {
        return self::where('app_id', $appId)
            ->get();
    }

    /**
     * 根据Hook名称获取Hook信息
     *
     * @param string $name
     * @return array
     */
    public function findByName($name)
    {
        return self::where('name', $name)
            ->get();
    }

    /**
     * 根据hook name查找hook注册信息，返回hook数据
     *
     * @param string $names
     * @return array
     */
    public function batchFindByName($names)
    {
        return self::whereIn('name', $names)
            ->get();
    }

    /**
     * 分页查找钩子信息
     *
     * @param int $num 默认为10
     * @param int $start
     * @param int $index
     * @param string $order
     * @return boolean|Ambigous <multitype:, multitype:multitype: Ambigous <multitype:, multitype:unknown , mixed> >
     */
    public function findByPage($perpage = 10, $start = 0, $index = 'name', $order = 'name')
    {
        return self::orderby($order, 'asc')
            ->paginate($perpage);
    }

    /**
     * 根据hook名称搜索
     *
     * @param string $name
     * @param int $num
     * @param int $start
     * @return array
     */
    public function searchHook($fields, $perpage = 10, $start = 0)
    {
        $sql = self::whereRaw('1=1');
        $sql = $this->_buildCondition($sql, $fields);

        return $sql->paginate($perpage);
    }

    /**
     * 获取数据总条数
     *
     * @return int
     */
    public function count()
    {
       return self::count();
    }

    private function _buildCondition($sql, $fields)
    {
        foreach ($fields as $k => $v) {
            switch ($k) {
                case 'name':
                    $sql = $sql->where('name', 'like', "$v%");
                    break;
                case 'app_name':
                    $sql = $sql->where('app_name', 'like', "%$v%");
                    break;
            }
        }
        return $sql;
    }
}

?>