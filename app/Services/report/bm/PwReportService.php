<?php

namespace App\Services\report\bm;

use App\Core\ErrorBag;
use App\Core\Tool;
use App\Services\report\bs\PwReport;
use App\Services\user\bs\PwUser;
use Core;

/**
 * 举报
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class PwReportService
{

    const REPORT_TYPE_THREAD = 1;
    const REPORT_TYPE_POST = 2;
    const REPORT_TYPE_MESSAGE = 3;
    const REPORT_TYPE_PHOTO = 4;

    /**
     * 获取举报列表
     *
     * @param int $ifcheck
     * @param string $type
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function getReceiverList($ifcheck, $type, $limit, $start)
    {
        $reposts = $this->_getReportDs()->getListByType($ifcheck, $type, $limit, $start);
        if (!$reposts) return array();
        $uids = $pids = $tmpArray = array();
        foreach ($reposts as $v) {
            $uids[] = $v['author_userid'];
            $uids[] = $v['created_userid'];
            $uids[] = $v['operate_userid'];
        }
        $users = $this->_getUserDs()->fetchUserByUid($uids);
        $reportType = $this->getTypeName();
        foreach ($reposts as $v) {
            $v['typeName'] = $reportType[$v['type']];
            $v['author_username'] = $users[$v['author_userid']]['username'];
            $v['created_username'] = $users[$v['created_userid']]['username'];
            $v['operate_username'] = $users[$v['operate_userid']]['username'];
            $tmpArray[] = $v;
        }
        return $tmpArray;
    }

    /**
     * 发送举报
     *
     * @param string $type
     * @param int $type_id
     * @param string $reason
     * @return bool
     */
    public function sendReport($type, $type_id, $reason)
    {
        $action = $this->_getReportAction($type);
        if (!$action) return new ErrorBag('REPORT:type.undefined');
        $typeId = $this->_getTypeId($type);
        $loginUser = Core::getLoginUser();
        $dm = $action->buildDm($type_id);
        if (!$dm) return new ErrorBag('REPORT:data.error');
        $dm->setType($typeId)
            ->setTypeId($type_id)
            ->setCreatedUserid($loginUser->uid)
            ->setCreatedTime(Tool::getTime())
            ->setReason($reason);

        $result = $this->_getReportDs()->addReport($dm);
        if ($result instanceof ErrorBag) return $result;
        // 发通知
        return $this->sendNotice($dm->getData());
    }

    protected function _getReportAction($type)
    {
        if (!$type) return null;
        $type = strtolower($type);
        $className = sprintf('PwReport%s', ucfirst($type));
        if (class_exists($className, false)) {
            return new $className();
        }
        $fliePath = app()->path() . '/Services/report/bm/report/' . $className;
        include_once($fliePath);
        return new $className();
    }

    /**
     * 发送通知
     *
     * @param array $data
     * @param int $fid
     * @param string $hrefUrl
     * @return array
     */
    public function sendNotice($data, $extendParams = null)
    {
        $reportType = array_flip($this->getTypeMap());
        $type = $reportType[$data['type']];
        $receivers = $this->getReceiver($type);

        if (!$receivers) return false;
        if (!$extendParams) {
            $uids = array($data['author_userid'], $data['created_userid']);
            $users = $this->_getUserDs()->fetchUserByUid($uids);
            $extendParams = array(
                'fromUser' => $users[$data['created_userid']]['username'],
                'fromUserId' => $users[$data['created_userid']]['uid'],
                'username' => $users[$data['author_userid']]['username'],
                'authorId' => $users[$data['author_userid']]['uid'],
                'content' => $data['content'],
                'type' => $data['type'],
                'type_id' => $data['type_id'],
                'hrefUrl' => $data['content_url'],
                'reason' => $data['reason'],
            );
        }
        $notice = app('message.srv.PwNoticeService');
        foreach ($receivers as $uid) {
            $notice->sendNotice($uid, 'report_' . $type, $data['type_id'], $extendParams);
        }
        return true;
    }

    /**
     * 获取举报消息发送对象
     *
     * @param int $fid
     * @return array
     */
    private function getReceiver($type)
    {
        $_maxUids = 30;
        $receivers = $this->_getReportDs()->getNoticeReceiver();
        $count = count($receivers);
        if ($count > $_maxUids) {
            return array_slice(array_unique($receivers), 0, $_maxUids);
        }
        $action = $this->_getReportAction($type);
        $users = $action->getExtendReceiver();
        $toUsers = array_merge($receivers, $users);
        return array_slice(array_unique($toUsers), 0, $_maxUids);
    }

    /**
     * 获取举报类型
     *
     * @return array
     */
    public function getTypeMap()
    {
        return array(
            'thread' => self::REPORT_TYPE_THREAD,
            'post' => self::REPORT_TYPE_POST,
            'message' => self::REPORT_TYPE_MESSAGE,
            'photo' => self::REPORT_TYPE_PHOTO
        );
    }

    /**
     * 获取举报类型名称
     *
     * @return array
     */
    public function getTypeName()
    {
        return array(
            self::REPORT_TYPE_THREAD => '帖子',
            self::REPORT_TYPE_POST => '回复',
            self::REPORT_TYPE_MESSAGE => '消息',
            self::REPORT_TYPE_PHOTO => '照片'
        );
    }

    private function _getTypeId($typeName)
    {
        $types = $this->getTypeMap();
        if (!isset($types[$typeName])) return false;
        return $types[$typeName];
    }

    /**
     * @return PwUser
     */
    protected function _getUserDs()
    {
        return app(PwUser::class);
    }

    /**
     * @return PwReport
     */
    protected function _getReportDs()
    {
        return app(PwReport::class);
    }
}

