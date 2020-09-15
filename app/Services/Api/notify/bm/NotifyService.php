<?php

namespace App\Services\Api\notify\bm;

use App\Core\Tool;
use App\Services\Api\base\WindidUtility;
use App\Services\Api\notify\bs\Notify;
use App\Services\Api\notify\bs\NotifyLog;
use App\Services\Api\notify\dm\NotifyLogDm;

/**
 * 客户端通知服务
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: WindidNotifyService.php 29741 2013-06-28 07:54:24Z gao.wanggao $
 * @package
 */
class NotifyService
{

    protected function getOperation($method)
    {
        $config = include app()->path() . '/Services/Api/base/WindidNotifyConf.php';
        $operation = '';
        foreach ($config AS $k => $v) {
            if ($v['method'] == $method) {
                $operation = $k;
                break;
            }
        }
        return $operation;
    }

    /**
     * 写入通知信息
     *
     * @param int $method
     * @param array $data
     * @param bool $iswindid
     */
    public function send($method, $data, $appid = 0)
    {
        if (!$operation = $this->getOperation($method)) {
            return false;
        }
        if (!$nid = $this->_getNotifyDs()->addNotify($appid, $operation, serialize($data), Tool::getTime())) {
            return false;
        }
        $apps = $this->_getAppDs()->getList();
        $dms = array();
        foreach ($apps as $val) {
            if (!$val['isnotify'] || $val['id'] == $appid) continue;
            $dm = new NotifyLogDm();
            $dm->setAppid($val['id'])->setNid($nid);
            $dms[] = $dm;
        }
        $this->_getNotifyLogDs()->multiAddLog($dms);
        register_shutdown_function(array(&$this, 'shutdownSend'), $nid);
        return true;
    }

    public function shutdownSend($nid)
    {
        $url = Core::app('windid')->url->base . '/index.php?m=queue';
        WindidUtility::buildRequest($url, array('nid' => $nid), false, 10);
        return true;
    }

    /**
     * 同步登录登出
     *
     * @param string $notify
     * @param int $uid
     */
    public function syn($method, $uid, $appid = 0)
    {
        $operation = $this->getOperation($method);
        $time = Tool::getTime();
        $data = array();
        $apps = $this->_getAppDs()->getList();

        $syn = false;
        foreach ($apps as $val) {
            if (!$val['issyn'] && $val['id'] == $appid) {
                $syn = true;
                break;
            }
            if (!$val['issyn'] || $val['id'] == $appid) continue;
            $array = array(
                'windidkey' => WindidUtility::appKey($val['id'], $time, $val['secretkey'], array('uid' => $uid), array()),
                'operation' => $operation,
                'uid' => $uid,
                'clientid' => $val['id'],
                'time' => $time
            );
            $data[] = WindidUtility::buildClientUrl($val['siteurl'], $val['apifile']) . http_build_query($array);
        }
        return $syn ? array() : $data;
    }


    private function _getAppDs()
    {
        return app('WSRV:app.WindidApp');
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