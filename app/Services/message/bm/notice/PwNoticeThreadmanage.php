<?php

namespace App\Services\message\bm\notice;

use App\Core\Tool;

class PwNoticeThreadmanage extends PwNoticeAction
{
    public $aggregate = false;

    public function buildTitle($param = 0, $extendParams = null, $aggregatedNotice = null)
    {
        return Tool::substrs($extendParams['content'], 80);
    }

    /**
     * 帖子管理通知相关扩展参数组装
     * @see PwNoticeAction::formatExtendParams()
     */
    public function formatExtendParams($extendParams, $aggregatedNotice = null)
    {
        return $extendParams;
    }

    public function getDetailList($notice)
    {

    }
}