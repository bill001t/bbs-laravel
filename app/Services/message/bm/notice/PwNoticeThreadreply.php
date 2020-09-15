<?php

namespace App\Services\message\bm\notice;

use App\Services\forum\bs\PwForum;
use App\Services\forum\bs\PwThread;
use App\Services\forum\dm\PwTopicDm;
use App\Services\user\bs\PwUser;

class PwNoticeThreadreply extends PwNoticeAction
{

    public $aggregate = true;
    public $ignoreNotice = true;

    public function buildTitle($param = 0, $extendParams = null, $aggregatedNotice = null)
    {
        return '回复提醒';
    }

    /**
     * 回复提醒相关扩展参数组装
     * @see PwNoticeAction::formatExtendParams()
     */
    public function formatExtendParams($extendParams, $aggregatedNotice = null)
    {
        if (!$aggregatedNotice || $aggregatedNotice['is_read']) {
            $extendParams['replyUser'] = array($extendParams['replyUserid'] => $extendParams['replyUsername']);
            return $extendParams;
        }

        $oldExtendParams = @unserialize($aggregatedNotice['extend_params']);

        //处理uids
        if (is_array($oldExtendParams['replyUser'])) {
            if (count($oldExtendParams['replyUser']) > 3) array_pop($oldExtendParams['replyUser']);

            if (false !== ($key = array_search($extendParams['replyUserid'], array_keys($oldExtendParams['replyUser'])))) {
                unset($oldExtendParams['replyUser'][$key]);
            }
            $oldExtendParams['replyUser'][$extendParams['replyUserid']] = $extendParams['replyUsername'];
            $extendParams['replyUser'] = $oldExtendParams['replyUser'];
            $extendParams['pid'] = $oldExtendParams['pid'];
        }

        return $extendParams;
    }

    /**
     *
     * 忽略一个回复通知
     * @param array $notice
     */
    public function ignoreNotice($notice, $ignore = 1)
    {
        if (!$notice) {
            return false;
        }

        $dm = new PwTopicDm($notice['param']);
        $dm->setReplyNotice($ignore ? 0 : 1);
        $this->_getThreadDs()->updateThread($dm);
    }

    /**
     * 获取主题及最新回复
     * @see PwNoticeAction::getDetailList()
     */
    public function getDetailList($notice)
    {
        $list = array();
        if (!$notice || !$notice['param']) {
            return $list;
        }
        $list['replyUsers'] = app(PwUser::class)->fetchUserByUid($notice['extend_params']['uids'], PwUser::FETCH_MAIN);

        /*		$list['thread'] = $this->_getThreadDs()->getThread($notice['param'],PwThread::FETCH_ALL);
                $list['newreplies'] = $this->_getThreadDs()->getPostByTid($notice['param'],0,20,false);
                $list['thread']['fid'] && $list['forum'] = $this->_getForumDs()->getForum($list['thread']['fid']);*/
        return $list;
    }

    /**
     *
     * Enter description here ...
     * @return PwForum
     */
    private function _getForumDs()
    {
        return app(PwForum::class);
    }

    /**
     *
     * Enter description here ...
     * @return PwThread
     */
    private function _getThreadDs()
    {
        return app(PwThread::class);
    }
}