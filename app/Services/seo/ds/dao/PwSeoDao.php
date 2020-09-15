<?php

namespace App\Services\seo\ds\dao;

use App\Services\seo\ds\relation\PwSeo;

/**
 * Pw_seo表的dao
 *
 * @author Shi Long <long.shi@alibaba-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id$
 * @package service.seo.dao
 */
class PwSeoDao extends PwSeo
{
    /**
     * 批量更新或添加seo数据
     *
     * @param array $data
     * @return boolean
     */
    public function batchReplaceSeo($data)
    {
        if (!is_array($data)) return false;
        $tmp = array();
        foreach ($data as $v) {
            $v = $this->_filterStruct($v);
            $v && $tmp[] = array(
                $v['mod'],
                $v['page'],
                $v['param'] ? $v['param'] : 0,
                $v['title'],
                $v['keywords'],
                $v['description']);
        }
        if (empty($tmp)) return false;

        return self::firstOrCreate($tmp);
    }

    /**
     * 获取单条记录
     *
     * @param string $mod
     * @param string $page
     * @param string $param
     * @return array
     */
    public function getByModAndPageAndParam($mod, $page, $param)
    {
        return self::where('mod', $mod)
            ->where('page', $page)
            ->where('param', $param)
            ->first();
    }

    /**
     * 根据模式和页面批量获取
     *
     * @param string $mod
     * @param string $page
     * @return array
     */
    public function getByModAndPage($mod, $page)
    {
        return $this->_buildResult(self::where('mod', $mod)
            ->where('mod', $page)
            ->get());
    }

    /**
     * 根据模式获取
     *
     * @param string $mod
     * @return array
     */
    public function getByMod($mod)
    {
        return $this->_buildResult(self::where('mod', $mod)
            ->get());
    }

    /**
     * 根据参数获取多个seo数据
     *
     * @param string $mod
     * @param string $page
     * @param array $params
     * @return array
     */
    public function getByParams($mod, $page, $params = array())
    {
        return $this->_buildResult(self::where('mod', $mod)
            ->where('page', $page)
            ->whereIn('param', $params)
            ->get());
    }

    /**
     * 组装数据
     *
     * @param array $result
     * @return array
     */
    private function _buildResult($result)
    {
        $seo = array();
        foreach ($result as $v) {
            if (!$v['param'])
                $seo[$v['page']][0] = array(
                    'title' => $v['title'],
                    'keywords' => $v['keywords'],
                    'description' => $v['description']);
            else
                $seo[$v['page']][$v['param']] = array(
                    'title' => $v['title'],
                    'keywords' => $v['keywords'],
                    'description' => $v['description']);
        }
        return $seo;
    }

}

?>