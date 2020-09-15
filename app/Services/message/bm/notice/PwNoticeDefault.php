<?php

namespace App\Services\message\bm\notice;

use App\Core\Tool;

class PwNoticeDefault extends PwNoticeAction
{

    public $aggregate = false;
    public $ignoreNotice = false;

    public function buildTitle($param = 0, $extendParams = null, $aggregatedNotice = null)
    {
        if ($extendParams['title']) {
            $title = $extendParams['title'];
        } else {
            $title = Tool::substrs($extendParams['content'], 60);
        }
        return $title;
    }

    /**
     * @see PwNoticeAction::formatExtendParams()
     */
    public function formatExtendParams($extendParams, $aggregatedNotice = null)
    {
        return $extendParams;
    }

    /**
     *
     * 忽略一个回复通知
     * @param array $notice
     */
    public function ignoreNotice($notice, $ignore = 1)
    {
    }

    /**
     * @see PwNoticeAction::getDetailList()
     */
    public function getDetailList($notice)
    {
    }
}