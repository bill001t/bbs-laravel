<?php

namespace App\Services\message\bm\notice;

use App\Core\Utility;

/**
 * 私信举报
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class PwNoticeReport extends PwNoticeAction
{

    public $aggregate = true;

    public function buildTitle($param = 0, $extendParams = null, $aggregatedNotice = null)
    {
        return '举报';
    }

    /**
     *
     * @see PwNoticeAction::formatExtendParams()
     */
    public function formatExtendParams($extendParams, $aggregatedNotice = null)
    {
        $oldExtendParams = $aggregatedNotice ? unserialize($aggregatedNotice['extend_params']) : $extendParams;
        return Utility::mergeArray($oldExtendParams, $extendParams);
    }

    public function getDetailList($notice)
    {

    }
}