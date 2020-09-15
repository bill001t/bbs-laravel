<?php

namespace App\Services\nav\ds\dao;

use App\Services\nav\ds\relation\Nav;

class PwNavDao extends Nav
{
    /**
     * 根据ID获取一条导航信息
     *
     * @param int $navId ID
     * @return array
     */
    public function getNav($navid)
    {
        return self::find($navid);
    }

    /**
     * 获取多条导航信息
     *
     * @param array $navids
     * @return Ambigous <multitype:, multitype:multitype: Ambigous <multitype:, multitype:unknown , mixed> >
     */
    public function fetchNav($navids)
    {
        return self::whereIn('navid', $navids);
    }

    /**
     * 获取某类型导航列表
     *
     * @param string $type 导航类型
     * @param int $isShow 是否显示
     * @return array
     */
    public function getNavByType($type, $isShow)
    {
        $sql = self::where('type', $type);

        if ($isShow < 2) {
            $sql = $sql->where('isshow', $isShow);
        }

        return $sql->orderby('rootid', 'asc')
            ->orderby('parentid', 'asc')
            ->orderby('orderid', 'asc')
            ->get();
    }

    public function getNavBySign($type, $sign)
    {
        return self::where('type', $type)
            ->where('sign', $sign)
            ->first();
    }

    /**
     * 获取顶级导航列表
     *
     * @param string $type 导航类型
     * @return array
     */
    public function getRootNav($type, $isShow)
    {
        $sql = self::where('type', $type)
            ->where('parentid', 0);

        if ($isShow < 2) {
            $sql = $sql->where('isshow', $isShow);
        }

        return $sql->orderby('orderid', 'asc')
            ->get();
    }

    /**
     * 获取顶级导航的子导航列表
     *
     * @param int $navId 父导航ID
     * @return array
     */
    public function getChildNav($navId, $isShow)
    {
        $sql = self::where('parentid', 0);

        if ($isShow < 2) {
            $sql = $sql->where('isshow', $isShow);
        }

        return $sql->orderby('orderid', 'asc')
            ->get();
    }

    /**
     * 获取导航最大排序
     *
     * @param string $type 导航类型
     * @param int $parentid 父ID
     * @return int
     */
    public function getNavMaxOrder($type = '', $parentid = 0)
    {
        return self::where('type', $type)
            ->where('parentid', 0)
            ->max('orderid');
    }

    /**
     * 添加一条导航
     *
     * @param array $data
     * @return int
     */
    public function addNav($data)
    {
        return self::create($data);
    }

    /**
     * 修改一条导航
     *
     * @param array $data
     * @return bool
     */
    public function updateNav($navid, $data)
    {
        return self::where('navid', $navid)
            ->update($data);
    }

    /**
     * 删除一条导航
     *
     * @param int $navId
     * @return bool
     */
    public function delNav($navid)
    {
        return self::destroy($navid);
    }

}