<?php

namespace App\Http\Controllers\bbs\controller;

use App\Http\Controllers\Controller;
use App\Services\forum\bs\PwForum;
use App\Services\site\bs\PwBbsinfo;
use Core;

/**
 * 版块列表页
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: ForumlistController.php 23994 2013-01-18 03:51:46Z long.shi $
 * @package srcapplications.bbs.controller
 */
class ForumListController extends Controller
{

    public $todayposts = 0;
    public $article = 0;

    /* (non-PHPdoc)
     * @see WindController::run()
     */
    public function run()
    {
        /* @var $forumDs PwForum */
        $forumDs = app(PwForum::class);
        $list = $forumDs->getCommonForumList(PwForum::FETCH_MAIN | PwForum::FETCH_STATISTICS);

        list($cateList, $forumList) = $this->_filterMap($list);
        $bbsinfo = app(PwBbsinfo::class)->getInfo(1);

        return view('bbs.forum_list')
            ->with('cateList', $cateList)
            ->with('forumList', $forumList)
            ->with('todayposts', $this->todayposts)
            ->with('article', $this->article)
            ->with('bbsinfo', $bbsinfo);

        //seo设置
        /*Wind::import('SRV:seo.bo.PwSeoBo');
        $seoBo = PwSeoBo::getInstance();
        $seoBo->init('bbs', 'forumlist');
        Core::setV('seo', $seoBo);*/
    }

    /**
     * 过滤版块信息
     * 1、过滤掉不显示的版块
     *
     * @param array $list
     * @return array
     */
    private function _filterMap($list)
    {
        $cate = $forum = array();
        foreach ($list as $_key => $_item) {
            if (1 != $_item['isshow']) continue;
            $_item['manager'] = $this->_setManages(array_unique(explode(',', $_item['manager'])));
            if ($_item['parentid'] == 0) {
                $cate[$_key] = $_item;
                isset($forum[$_key]) || $forum[$_key] = array();
                $this->todayposts += $_item['todayposts'];
                $this->article += $_item['article'];
            } else {
                $forum[$_item['parentid']][$_key] = $_item;
            }
        }
        return array($cate, $forum);
    }

    /**
     * 设置版块的版主UID
     *
     * @param array $manage
     * @param array $userList
     * @return array
     */
    private function _setManages($manage)
    {
        $_manage = array();
        foreach ($manage as $_v) {
            if ($_v) $_manage[] = $_v;
        }
        return $_manage;
    }
}