<?php

namespace App\Services\message\bm\notice;

use App\Core\Tool;
use App\Services\dialog\bs\MessageApi;
use App\Services\message\bm\PwMessageService;
use App\Services\user\bs\PwUser;

class PwNoticeMessage extends PwNoticeAction
{

    public $aggregate = true;

    public function buildTitle($param = 0, $extendParams = null, $aggregatedNotice = null)
    {
        return Tool::substrs(strip_tags($extendParams['content']), 28);
    }

    /**
     * 帖子管理通知相关扩展参数组装
     * @see PwNoticeAction::formatExtendParams()
     */
    public function formatExtendParams($extendParams, $aggregatedNotice = null)
    {
        $fromUserInfo = $this->_getUserDs()->getUserByUid($extendParams['from_uid']);
        $extendParams['from_username'] = $fromUserInfo['username'];
        return $extendParams;
    }

    public function getDetailList($notice)
    {
        $list = array();
        if (!$notice || !$notice['param']) {
            return $list;
        }
        $dialog = $this->_getMessagesService()->getDialogByUid($notice['uid'], $notice['param']);
        if (!$dialog) return $list;
        //$list = $this->_getMessagesDs()->getDialogMessages($notice['uid'], $notice['param'], 0, 20);
        $list = $this->_getMessageApi()->getMessageList($dialog['dialog_id'], 0, 20);
        krsort($list);
//		$list['newreplies'] = $this->_getThreadDs()->getPostByTid($notice['param'],0,20,false);
        $num = $this->_getMessageApi()->read($notice['uid'], $dialog['dialog_id'], array_keys($list));
        if ($num) {
            //$this->_getMessagesService()->resetDialogMessages($dialog['dialog_id']);
            $this->_getMessagesService()->resetUserMessages($dialog['to_uid']);
        }
        return array('data' => $list, 'dialog' => $dialog);
    }

    /**
     *
     * @return PwMessageMessages
     */
    private function _getMessageApi()
    {
        return app(MessageApi::class);
    }

    /**
     *
     * @return PwMessageService
     */
    private function _getMessagesService()
    {
        return app(PwMessageService::class);
    }

    /**
     *
     * @return PwUser
     */
    private function _getUserDs()
    {
        return app(PwUser::class);
    }
}