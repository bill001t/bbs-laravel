<?php

namespace App\Services\Api\notify\bm;

use App\Core\Tool;
use App\Core\Utility;
use App\Services\Api\notify\bs\Notify;
use App\Services\Api\notify\bs\NotifyLog;
use App\Services\Api\notify\dm\NotifyLogDm;
use App\Services\user\bs\PwUser;

class NotifyServer
{

    protected $logId = array();

    public function send()
    {
        $this->logId = array();
        $i = 0;
        do {
            $result = $this->_queueSend($i);
        } while ($result && ++$i < 5);

        $this->_updateLog($this->logId);
        return true;
    }

    public function sendByNid($nid)
    {
        $logDs = $this->_getNotifyLogDs();
        if (!$queue = $logDs->getList(0, $nid, 0, 0, 0)) {
            return false;
        }
        $result = $this->_request($queue);
        $this->_updateLog($result);
        return true;
    }


    public function logSend($logid)
    {
        $logDs = $this->_getNotifyLogDs();
        if (!$log = $logDs->getLog($logid)) {
            return false;
        }
        $result = $this->_request(array($logid => $log));
        $this->_updateLog($result);
        return trim(current($result)) == 'success' ? true : false;
    }

    /**
     * 通知客户端
     *
     * @param int $i 通知次数
     * @return bool
     */
    protected function _queueSend($nums)
    {
        $logDs = $this->_getNotifyLogDs();
        if (!$queue = $logDs->getUncomplete(10, $nums * 10)) {
            return false;
        }
        if ($nums > 0) sleep(3);
        $this->logId += $this->_request($queue);
        return true;
    }

    protected function _request($queue)
    {
        $time = Tool::getTime();
        $appids = $nids = array();
        foreach ($queue as $v) {
            $appids[] = $v['appid'];
            $nids[] = $v['nid'];
        }
        $apps = $this->_getAppDs()->fetchApp(array_unique($appids));
        $notifys = $this->_getNotifyDs()->fetchNotify(array_unique($nids));

        $post = $urls = array();

        foreach ($queue as $k => $v) {
            $appid = $v['appid'];
            $nid = $v['nid'];
            $post[$k] = unserialize($notifys[$nid]['param']);
            $array = array(
                'windidkey' => Utility::appKey($v['appid'], $time, $apps[$appid]['secretkey'], array('operation' => $notifys[$nid]['operation']), $post[$k]),
                'operation' => $notifys[$nid]['operation'],
                'clientid' => $v['appid'],
                'time' => $time
            );

            $urls[$k] = Utility::buildClientUrl($apps[$appid]['siteurl'], $apps[$appid]['apifile']) . http_build_query($array);
        }
        return Utility::buildMultiRequest($urls, $post);
    }

    protected function _updateLog($logs)
    {
        $logDs = $this->_getNotifyLogDs();
        foreach ($logs as $k => $v) {
            $dm = new NotifyLogDm($k);
            if (trim($v) == 'success') {
                $dm->setComplete(1)->setIncreaseSendNum(1);
            } else {
                $dm->setComplete(0)->setIncreaseSendNum(1)->setReason('fail');
            }
            $logDs->updateLog($dm);
        }
        return true;
    }

    private function _getUserDs()
    {
        return app(PwUser::class);
    }

    private function _getNotifyDs()
    {
        return app(Notify::class);
    }

    private function _getNotifyLogDs()
    {
        return app(NotifyLog::class);
    }
}

?>
