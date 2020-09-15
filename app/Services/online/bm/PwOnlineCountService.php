<?php

namespace App\Services\online\bm;

use App\Core\Tool;
use App\Services\online\bs\PwGuestOnline;
use App\Services\online\bs\PwOnlineStatistics;
use App\Services\online\bs\PwUserOnline;
use Core;

/**
 * 在线服务统计接口
 *
 * the last known user to change this file in the repository  <$LastChangedBy: jieyin $>
 * @author $Author: jieyin $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwOnlineCountService.php 18618 2012-09-24 09:31:00Z jieyin $
 * @package
 */
class PwOnlineCountService
{

    /**
     * Statistics记录更新间隔时间
     *
     */
    public $cacheTime = 600;

    /**
     * 获得最新的在线用户列表
     *
     * @param $fid
     * @param $limit
     * @return array
     */
    public function getLastVisitor($fid = 0, $limit = 10)
    {
        return app(PwUserOnline::class)->getInfoList($fid, 0, $limit);
    }

    /**
     * 分页显示在线用户列表
     *
     * @param int $fid
     * @param int $page
     * @param int $limit
     * @param bool $isExact 是否需要获取精确的记录
     * @return array
     */
    public function getVisitorList($fid = 0, $page = 1, $limit = 10, $isExact = false)
    {
        $data = array();
        $ds = app(PwUserOnline::class);
        $count = $ds->getOnlineCount($fid);
        if ($count < 1) {
            return array(0, array());
        }
        list($start, $limit) = Tool::page2limit($page, $limit);
        $list = $ds->getInfoList($fid, $start, $limit);
        if ($isExact) {
            $time = Tool::getTime();
            $config = Core::C('site');
            if ($config['onlinetime'] > 0) {
                $expire = $time - $config['onlinetime'] * 60;
                foreach ($list AS $k => $v) {
                    if ($v['modify_time'] <= $expire) unset($list[$k]);
                }
            }
        }
        return array($count, $list);
    }

    /**
     * 统计在线用户人数
     *
     * $fid $tid都为0时统计总在线人数
     * @param int $fid
     * @param int $tid
     * @return int
     */
    public function getUserOnlineCount($fid = 0, $tid = 0)
    {
        $time = Tool::getTime();
        if ($fid > 0 && $tid <= 0) {
            $signkey = 'forum_' . $fid . '_user';
        } elseif ($fid <= 0 && $tid > 0) {
            $signkey = 'thread_' . $tid . '_user';
        } else {
            $signkey = 'site_user';
        }
        $ds = app(PwOnlineStatistics::class);
        $statist = $ds->getInfo($signkey);
        $number = isset($statist['number']) ? $statist['number'] : 0;
        if ($number > 0 && ($statist['created_time'] + $this->cacheTime) > $time) {
            return $statist['number'];
        } else {
            $count = app(PwUserOnline::class)->getOnlineCount($fid, $tid);
            $ds->addInfo($signkey, $count);
            if ($count > $number && $signkey == 'site_user') {
                $ds->addInfo('max_user', $count, $time);
            }
            return $count;
        }

    }

    /**
     * 统计在线游客人数
     *
     * $fid $tid都为0时统计总在线人数
     * @param int $fid
     * @param int $tid
     * @return int
     */
    public function getGuestOnlineCount($fid = 0, $tid = 0)
    {
        $time = Tool::getTime();
        if ($fid > 0 && $tid <= 0) {
            $signkey = 'forum_' . $fid . '_guest';
        } elseif ($fid <= 0 && $tid > 0) {
            $signkey = 'thread_' . $tid . '_guest';
        } else {
            $signkey = 'site_guest';
        }
        $ds = app(PwOnlineStatistics::class);
        $statist = $ds->getInfo($signkey);
        $number = isset($statist['number']) ? $statist['number'] : 0;
        if ($number > 0 && ($statist['created_time'] + $this->cacheTime) > $time) {
            return $statist['number'];
        } else {
            $count = app(PwGuestOnline::class)->getOnlineCount($fid, $tid);
            $ds->addInfo($signkey, $count);
            if ($count > $number && $signkey == 'site_guest') {
                $ds->addInfo('max_guest', $count, $time);
            }
            return $count;
        }
    }

    /**
     * 获取历史最高在线信息
     *
     * @return int
     */
    public function getMaxOnline()
    {
        $ds = app(PwOnlineStatistics::class);
        $maxUser = $ds->getInfo('max_user');
        $maxGuest = $ds->getInfo('max_guest');
        $time = $maxUser['created_time'] > $maxGuest['created_time'] ? $maxUser['created_time'] : $maxGuest['created_time'];
        $number = $maxUser['number'] + $maxGuest['number'];
        return array('signkey' => 'maxonline',
            'created_time' => $time,
            'number' => $number
        );
    }

}

?>