<?php

namespace App\Services\message\bm;

use App\Services\user\dm\PwUserInfoDm;
use App\Services\user\bs\PwUserBlack;
use App\Services\user\bs\PwUserBehavior;
use App\Services\attention\bs\PwAttention;
use App\Services\dialog\bs\MessageApi;
use App\Services\message\bs\PwMessageMessages;
use App\Services\user\bs\PwUser;
use App\Services\credit\bo\PwCreditBo;
use Core;
use App\Core\ErrorBag;
use App\Core\Security;
use App\Core\Tool;
use App\Core\Hook\HookService;

/**
 * 私信业务
 *
 * @author peihong <peihong.zhangph@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwMessageService.php 3833 2012-01-12 03:32:27Z peihong.zhangph $
 * @package src.service.message.srv
 */
class PwMessageService
{
    private static $_hookInstance = null;

    /**
     * 按用户名发送私信
     *
     * @param string $username
     * @param content $content
     * @param int $from_uid
     * @return ErrorBag|boolean
     */
    public function sendMessage($username, $content, $fromUid = 0)
    {
        $loginUser = Core::getLoginUser();
        $fromUid or $fromUid = $loginUser->uid;
        $userInfo = $this->_getUserDs()->getUserByName($username);
        if (!$userInfo) return new ErrorBag('Message:user.notfound');
        // 检测是否隐私设置
        $userInfos = array($userInfo['uid'] => $userInfo);
        $result = $this->_checkPrivate($userInfos);
        if ($result instanceof ErrorBag) {
            return $result;
        }

        // 检测今天发了多少
        list($result, $sendnum, $maxnum) = $this->_checkTodayNum($loginUser, $result);
        if (!$result) {
            return new ErrorBag('MESSAGE:message_max_send.error', ['{sendnum}' => $sendnum, '{maxnum}' => $maxnum]);
        }
        return $this->sendMessageByUid($result[0], $content, $fromUid);
    }

    /**
     * 检测隐私
     *
     * @param array $userInfos 以uid为key的二维数组
     * @return ErrorBag|boolean
     */
    private function _checkPrivate($userInfos)
    {
        $uids = array_keys($userInfos);
        // 检测是否有设置粉丝才能收
        $result = $this->_checkMessageFan($uids);
        if ($result !== true) {
            if (count($result) == count($uids)) {
                return new ErrorBag('Message:private.fan.only');
            }
            $uids = array_diff($uids, $result);
        }
        // 检测是否有设置黑名单
        $result = app(PwUserBlack::class)->checkUserBlack($this->_getLoginUserId(), $uids);
        if ($result) {
            if (count($result) == count($uids)) {
                return new ErrorBag('Message:private.black');
            }
            $uids = array_diff($uids, $result);
        }
        return $uids;
    }

    /**
     * 按用户名群发送私信
     *
     * @param array $usernames
     * @param content $content
     * @return ErrorBag|boolean
     */
    public function sendMessageByUsernames($usernames, $content, $fromUid = 0)
    {
        $loginUser = Core::getLoginUser();
        $fromUid or $fromUid = $loginUser->uid;
        // 发消息前hook
        if (($result = $this->_getHook()->runWithVerified('check', $fromUid, $content)) instanceof ErrorBag) {
            return $result;
        }
        $userInfos = $this->_getUserDs()->fetchUserByName($usernames);
        if (!$userInfos) {
            return new ErrorBag('MESSAGE:user.notfound');
        }
        // 检测是否隐私设置
        $result = $this->_checkPrivate($userInfos);
        if ($result instanceof ErrorBag) {
            return $result;
        }
        // 检测今天发了多少
        list($result, $sendnum, $maxnum) = $this->_checkTodayNum($loginUser, $result);
        if (!$result) {
            return new ErrorBag('MESSAGE:message_max_send.error', ['{sendnum}' => $sendnum, '{maxnum}' => $maxnum]);
        }
        foreach ($result as $uid) {
            $this->sendMessageByUid($uid, $content, $fromUid);
        }
        return true;
    }

    /**
     *
     * 批量标记会话已读
     * @param array $dialogIds
     */
    public function markDialogReaded($dialogIds)
    {
        if (!is_array($dialogIds) || !$dialogIds) return false;
        return $this->_getMessageApi()->readDialog($dialogIds);
    }

    /**
     * 按用户ID发送私信
     *
     * @param int $uid
     * @param string $content
     * @return ErrorBag|boolean
     */
    public function sendMessageByUid($uid, $content, $fromUid = 0)
    {
        if (!$uid) return new ErrorBag('MESSAGE:user.empty');
        $fromUid or $fromUid = $this->_getLoginUserId();
        if ($uid == $fromUid) {
            return new ErrorBag('MESSAGE:send.to.myself');
        }

       /* Wind::import('LIB:ubb.PwUbbCode');
        $content = PwUbbCode::autoUrl($content, true);*/

        // 发消息前hook
        if (($result = $this->_getHook()->runWithVerified('check', $fromUid, $content, $uid)) instanceof ErrorBag) {
            return $result;
        }
        $result = $this->_getMessageApi()->send($uid, $content, $fromUid);
        if ($result < 1) {
            return new ErrorBag('WINDID:code.' . $result);
        }

        $creditBo = PwCreditBo::getInstance();
        $creditBo->operate('sendmsg', new PwUserBo($fromUid));
        // 发消息扩展
        $this->_getHook()->runDo('addMessage', $uid, $fromUid, $content);

        if ($result) {
            //发件人通知
            $params = array('from_uid' => $uid, 'to_uid' => $fromUid, 'content' => $content, 'is_send' => 1);
            $this->_getNoticeService()->sendNotice($fromUid, 'message', $uid, $params, false);

            //收件人通知
            $params = array('from_uid' => $fromUid, 'to_uid' => $uid, 'content' => $content);
            $this->_getNoticeService()->sendNotice($uid, 'message', $fromUid, $params, false);
            //更新用户表未读数
            $this->updateUserMessage($uid);
        }
        //记录每天发送数量
        $this->_getUserBehaviorDs()->replaceDayBehavior($fromUid, 'message_today', Tool::getTime());
        return $result;
    }

    /**
     * 根据uids群发消息
     *
     * @param array $uids
     * @param string $content
     * @return ErrorBag|boolean
     */
    public function sendMessagesByUids($uids, $content, $fromUid = 0)
    {
        $fromUid or $fromUid = $this->_getLoginUserId();
        if (!is_array($uids)) return false;
        foreach ($uids as $uid) {
            $this->sendMessageByUid($uid, $content, $fromUid);
        }
        return true;
    }


    /**
     *
     * 获取分组列表
     *
     * @param int $uid
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getDialogs($uid, $start, $limit)
    {
        $count = $this->_getMessageApi()->countDialog($uid);
        if (!$count) return array(0, array());
        $dialogs = $this->_getMessageApi()->getDialogList($uid, $start, $limit);
        return array($count, $dialogs);
    }

    /**
     *
     * 获取一条对话信息
     * @param int $dialogId
     */
    public function getDialog($dialogId)
    {
        return $this->_getMessageApi()->getDialog($dialogId);
    }

    /**
     *
     * 根据uid获取对话信息
     * @param int $toUid
     * @param int $fromUid
     */
    public function getDialogByUid($toUid, $fromUid)
    {
        return $this->_getMessageApi()->getDialogByUser($toUid, $fromUid);
    }

    /**
     * 获取对话消息列表
     *
     * @param int $uid
     * @param int $from_uid
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getDialogMessageList($dialogId, $limit, $start)
    {
        // 对话消息分页
        $count = $this->_getMessageApi()->countMessage($dialogId);
        if (!$count) return array(0, array());
        $_messages = $this->_getMessageApi()->getMessageList($dialogId, $start, $limit);
        $messages = array();
        foreach ($_messages as $k => $v) {
            $v['content'] = Security::escapeHTML($v['content']);
            $messages[$k] = $v;
        }
        krsort($messages);
        return array($count, $messages);
    }

    /**
     *
     * 重新计算用户私信数
     * @param int $uid
     */
    public function resetUserMessages($uid)
    {
        $uid = intval($uid);
        if ($uid < 1) return false;
        $unread = $this->_getMessageApi()->getUnRead($uid);
        return $this->updateUserMessage($uid, $unread, false);
    }

    /**
     *
     * 删除会话
     * @param array $dialogIds
     */
    public function batchDeleteDialog($uid, $dialogIds)
    {
        return $this->_getMessageApi()->batchDeleteDialog($uid, $dialogIds);
    }

    /**
     *
     * 后台清理用户消息接口
     * @param int $uid
     * @param bool $message 是否删除私信
     * @param bool $notice 是否删除通知
     */
    public function deleteUserMessages($uid, $message = true, $notice = true)
    {
        $rs1 = $message ? $this->_getMessageApi()->deleteUserMessages($uid) : true;
        $rs2 = $notice ? app(PwNoticeService::class)->deleteNoticeByUid($uid) : true;
        return $rs1 && $rs2;
    }

    /**
     * 搜索消息
     *
     * @param int $start
     * @param int $limit
     * @param int $from_uid
     * @param int $starttime
     * @param int $endtime
     * @return array
     */
    public function getMessagesByUid($start, $limit, $fromuid = '', $starttime = 0, $endtime = 0, $keyword = '')
    {
        $search = array();
        if ($fromuid !== null) $search['fromuid'] = $fromuid;
        if ($starttime) $search['starttime'] = $starttime;
        if ($endtime) $search['endtime'] = $endtime;
        if ($keyword !== null) $search['keyword'] = $keyword;
        return $this->_getMessageApi()->searchMessage($search, $start, $limit);

    }

    /**
     *
     * 后台根据搜索结果的message id删除消息
     */
    public function deleteMessageByMessageIds($messageIds)
    {
        return $this->_getMessageApi()->deleteByMessageIds($messageIds);
    }

    /**
     * 设置消息
     *
     * @param int $uid
     * @param array $data
     * @param int $message_tone
     * @return array
     */
    public function setMessageConfig($uid, $privacy, $notice_types, $message_tone)
    {
        $uid = intval($uid);
        $message_tone = intval($message_tone);
        if ($uid < 1) return false;
        $dm = new PwUserInfoDm($uid);
        $dm->setMessage_tone($message_tone);
        $this->_getUserDs()->editUser($dm, PwUser::FETCH_DATA);
        return $this->_getMessagesDs()->setMessageConfig($uid, (int)$privacy, $notice_types);
    }

    /**
     * 更新用户表未读数
     *
     * @param int $uid
     * @param int $num
     * @param bool $increase
     * @return int
     */
    public function updateUserMessage($uid, $num = 1, $increase = true)
    {
        //更新用户表未读数
        $dm = new PwUserInfoDm($uid);
        if ($increase) {
            $dm->addMessages($num);
        } else {
            $dm->setMessageCount($num);
        }

        /*!defined('WINDID_IS_NOTIFY') && define('WINDID_IS_NOTIFY', 1);
        Wind::import('LIB:utility.PwWindidStd');
        $std = PwWindidStd::getInstance('user');
        $std->setMethod('editDmUser', 1);*/

        return $this->_getUserDs()->editUser($dm, PwUser::FETCH_DATA);
    }

    public function synEditUser($uid)
    {
        if (!$unread = $this->_getMessageApi()->getUnRead($uid)) {
            return true;
        }

        $dm = new PwUserInfoDm($uid);
        $dm->setMessageCount($unread);

       /* Wind::import('LIB:utility.PwWindidStd');
        $std = PwWindidStd::getInstance('user');
        $std->setMethod('editDmUser', 1);*/

        if (($result = $this->_getUserDs()->editUser($dm, PwUser::FETCH_DATA)) !== true) {
            return false;
        }
        $message = current($this->_getMessageApi()->getUnreadDialogsByUid($uid, 1));
        $last_message = $message['last_message'] ? unserialize($message['last_message']) : array();
        //发件人通知
        $params = array('from_uid' => $last_message['to_uid'], 'to_uid' => $last_message['from_uid'], 'content' => $last_message['content'], 'is_send' => 1);
        $this->_getNoticeService()->sendNotice($message['from_uid'], 'message', $message['to_uid'], $params, false);

        //收件人通知
        $params = array('from_uid' => $last_message['to_uid'], 'to_uid' => $last_message['from_uid'], 'content' => $last_message['content']);
        $this->_getNoticeService()->sendNotice($message['to_uid'], 'message', $message['from_uid'], $params, false);

        return true;
    }

    /**
     * 检查发私信权限
     *
     * @param int $uid
     * @param int $blackUid
     */
    public function checkAddMessageRight(PwUserBo $user)
    {
        if ($user->uid < 1) {
            return new ErrorBag('USER:user.not.login');
        }
        if ($user->getPermission('message_allow_send') < 1) {
            return new ErrorBag('MESSAGE:allow_send.right.error');
        }
        return true;
    }

    /**
     * 检测是否粉丝
     *
     * @param array $uids
     * @return array | bool
     */
    private function _checkMessageFan($uids)
    {
        !is_array($uids) && $uids = array($uids);
        $loginUid = $this->_getLoginUserId();
        $configs = $this->_getMessagesDs()->fetchMessageConfig($uids);
        $privateFans = array();
        foreach ($configs as $v) {
            $v['privacy'] && $privateFans[] = $v['uid'];
        }
        if (!$privateFans) {
            return true;
        }
        $fans = app(PwAttention::class)->fetchFans($loginUid, $privateFans);
        $result = array_diff($privateFans, array_keys($fans));
        if ($result) {
            return $result;
        }
        return true;
    }

    /**
     * 检测今日发消息数量
     *
     * @param PwUserBo $user
     * @param int $countUser
     * @return ErrorBag | bool
     */
    private function _checkTodayNum(PwUserBo $user, $touids)
    {
        !is_array($touids) && $touids = array($touids);
        $behavior = $this->_getUserBehaviorDs()->getBehavior($user->uid, 'message_today');
        $dayMax = $user->getPermission('message_max_send');
        $countUser = count($touids);
        if ($behavior['number'] + $countUser > $dayMax) {
            $touids = array_slice($touids, 0, $dayMax - $behavior['number']);
        }
        return array($touids, $behavior['number'], $dayMax);
    }

    /**
     * 获得windidDS
     *
     * @return WindidUser
     */
    private function _getUserWindid()
    {
        return Tool::windid('user');
    }

    private function _getMessageApi()
    {
        return app(MessageApi::class);
    }

    private function _getNoticeService()
    {
        return app(PwNoticeService::class);
    }

    private function _getLoginUserId()
    {
        $loginUser = Core::getLoginUser();
        return $loginUser->uid;
    }

    private function _getUserDs()
    {
        return app(PwUser::class);
    }

    private function _getMessagesDs()
    {
        return app(PwMessageMessages::class);
    }

    /**
     *
     * @return PwHookService
     */
    private function _getHook()
    {
        if (self::$_hookInstance == null) {
            self::$_hookInstance = new HookService('PwMessageService', 'PwMessageDoBase');
        }
        return self::$_hookInstance;
    }


    /**
     * PwUserBehavior
     *
     * @return PwUserBehavior
     */
    private function _getUserBehaviorDs()
    {
        return app(PwUserBehavior::class);
    }
}