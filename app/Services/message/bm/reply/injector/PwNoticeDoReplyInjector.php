<?php

namespace App\Services\message\bm\reply\injector;

use App\Core\Hook\BaseHookInjector;
use App\Services\forum\bs\PwThread;
use Request;

App\Services\message\bm\reply\do\PwNoticeDoReply;

/**
 * Enter description here ...
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class PwNoticeDoReplyInjector extends BaseHookInjector
{

    public function run()
    {
        $pid = (int)Request::get('pid', 'post');
        $content = Request::get('atc_content', 'post');
        $post = app(PwThread::class)->getPost($pid);
        if (!$post['reply_notice']) return false;
        return new PwNoticeDoReply($this->bp, $post, $content);
    }


}