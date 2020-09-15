<?php

namespace App\Services\hook\ds\dao;

use App\Services\hook\ds\relation\HookInject;

class PwHookInjectDao extends HookInject
{

    /**
     * 添加钩子定义
     *
     * @param array $fields
     * @return boolean
     */
    public function add($fields)
    {
        return self::create($fields);

    }

    /**
     * 批量添加钩子扩展信息, 影响行数
     *
     * @param array $fields
     * @return int
     */
    public function batchAdd($fields)
    {
        return self::create($fields);
    }

    /**
     * 刪除hook，返回影响行数
     *
     * @param string $id
     * @return int
     */
    public function del($id)
    {
        return self::destroy($id);
    }

    /**
     * 根据钩子名称删除钩子定义
     *
     * @param string $alias
     * @return boolean
     */
    public function delByAlias($alias)
    {
        return self::where('alias', $alias)
            ->delete();
    }

    /**
     * 根据Inject id批量删除injector信息
     *
     * @param array $ids
     * @return Ambigous <rowCount, boolean, number>
     */
    public function batchDelById($ids)
    {
        return self::destroy($ids);
    }

    /**
     * 根据别名，批量删除injector
     *
     * @param array $alias
     * @return Ambigous <rowCount, boolean, number>
     */
    public function batchDelByAlias($alias)
    {
        return self::whereIn('alias', $alias)
            ->delete();
    }

    /**
     * 根据HookName删除injector
     *
     * @param string $hookName
     * @return Ambigous <rowCount, boolean, number>
     */
    public function delByHookName($hookName)
    {
        return self::where('hook_name', $hookName)
            ->delete();
    }

    /**
     * 根据HookName删除injector
     *
     * @param array $hookNames
     * @return Ambigous <rowCount, boolean, number>
     */
    public function batchDelByHookName($hookNames)
    {
        return self::whereIn('hook_name', $hookNames)
            ->delete();
    }

    /**
     * 根据钩子名称和扩展别名删除一个扩展
     *
     * @param string $alias
     * @param string $hookname
     * @return Ambigous <rowCount, boolean, number>
     */
    public function delByHookNameAndAlias($alias, $hookname)
    {
        return self::where('hook_name', $hookname)
            ->where('alias', $alias)
            ->delete();
    }

    /**
     * 根据hook id 更新，返回影响行数
     *
     * @param string $id
     * @param array $fields
     * @return int
     */
    public function _update($id, $fields)
    {
        return self::where('id', $id)
            ->update($fields);
    }

    /**
     * 根据ID查找hook注册信息，返回hook数据
     *
     * @param string $appId
     * @return array
     */
    public function find($id)
    {
        return self::find($id);

    }

    /**
     * 根据id数据批量获取hook数据
     *
     * @param array $ids
     * @return array
     */
    public function fetch($ids)
    {
        return self::whereIn('id', $ids)
            ->get();
    }

    /**
     * 根据HookName获取注入服务列表
     *
     * @param int $hookId
     * @return array
     */
    public function findByHookName($hookName)
    {
        return self::where('hook_name', $hookName)
            ->orderby('id');

    }

    /**
     * 根据HookName批量获取注入服务列表
     *
     * @param int $hookId
     * @return array
     */
    public function fetchByHookName($hookNames)
    {
        return self::whereIn('hook_name', $hookNames)
            ->get();
    }

    /**
     * 根据别名获取应用注如服务列表
     *
     * @param string $alias
     * @return array
     */
    public function findByAlias($alias)
    {
        return self::where('alias', $alias)
            ->get();
    }

    /**
     * 根据别名批量查找注册服务
     *
     * @param array $alias
     * @return Ambigous <rowCount, boolean, number>
     */
    public function batchFindByAlias($alias)
    {
        return self::whereIn('alias', $alias)
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
    public function findByPage($perpage = 10, $start = 0, $index = 'id', $order = 'alias')
    {
        return self::all()
            ->orderby($order)
            ->paginate($perpage);
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

    /**
     * 根据应用名称删除
     *
     * @param string $appName
     * @return Ambigous <rowCount, boolean, number>
     */
    public function deleteByAppName($appName)
    {
        return self::where('app_name', $appName)
            ->delete();
    }

    /**
     * 根据应用id删除
     *
     * @param string $appName
     * @return Ambigous <rowCount, boolean, number>
     */
    public function deleteByAppId($appid)
    {
        return self::where('app_id', $appid)
            ->delete();
    }

    /**
     * 根据appid获取应用注如服务列表
     *
     * @param string $appid
     * @return array
     */
    public function findByAppid($appid)
    {
        return self::where('app_id', $appid)
            ->get();
    }

}

?>