<?php

namespace App\Services\message\bm\notice;

use App\Core\MessageTool;
use App\Services\message\bm\PwNoticeService;

/**
 * 帐号管理--禁止/解禁消息扩展
 *
 * @author xiaoxia.xu <x_824@sina.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: PwNoticeCredit.php 22678 2012-12-26 09:22:23Z jieyin $
 * @package src.service.task.srv.notice
 */
class PwNoticeCredit extends PwNoticeAction
{

    public $aggregate = false;
    public $ignoreNotice = true;

    /* (non-PHPdoc)
     * @see PwNoticeAction::buildTitle()
     */
    public function buildTitle($param = 0, $extendParams = null, $aggregatedNotice = null)
    {
        $params = array();
        $msg = '';
        switch ($extendParams['change_type']) {
            //转账
            case 'transfer':
                $params['{username}'] = '<a href="' . url('space/index/run', array('uid' => $extendParams['fromUid'])) . '">' . $extendParams['fromUserName'] . '</a>';
                $params['{num}'] = $extendParams['num'];
                $params['{unit}'] = $extendParams['unit'];
                $params['{credit}'] = $extendParams['credit'];
                $msg = 'CREDIT:transfer.to.notice.format';
                break;
            //转换:
            case 'exchange':
                $params['{credit1}'] = $extendParams['credit1'];
                $params['{num1}'] = $extendParams['num1'];
                $params['{unit1}'] = $extendParams['unit1'];
                $params['{credit2}'] = $extendParams['credit2'];
                $params['{num2}'] = $extendParams['num2'];
                $params['{unit2}'] = $extendParams['unit2'];
                $msg = 'CREDIT:exchange.notice.format';
                break;
            case 'pay':
                $params['{credit}'] = $extendParams['credit'];
                $params['{num}'] = $extendParams['num'];
                $params['{unit}'] = $extendParams['unit'];
                $params['{price}'] = $extendParams['price'];
                $msg = 'CREDIT:pay.notice.format';
                break;
        }
        return app(MessageTool::class)->getMessage($msg, $params);
    }

    /* (non-PHPdoc)
     * @see PwNoticeAction::formatExtendParams()
     */
    public function formatExtendParams($extendParams, $aggregatedNotice = null)
    {
        return $extendParams;
    }

    /* (non-PHPdoc)
     * @see PwNoticeAction::getDetailList()
     */
    public function getDetailList($notice)
    {
        $notice['is_ignore'] = app(PwNoticeService::class)->isIgnoreNoticeType($notice['uid'], $notice['typeid']);

        return $notice;
    }

    /**
     * 忽略
     *
     * @param array $notice
     */
    public function ignoreNotice($notice, $ignore = 1)
    {
        if (!$notice) {
            return false;
        }
        return app(PwNoticeService::class)->setIgnoreNotice($notice['typeid'], $notice['uid'], $ignore);
    }
}