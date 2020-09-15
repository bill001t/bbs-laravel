<?php

namespace App\Services\log\bm\datasource;

/**
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com> Dec 3, 2012
 * @link http://www.phpwind.com
 * @copyright Copyright © 2003-2010 phpwind.com
 * @license
 */
class PwShieldTagDoWeibo extends iPwGleanDoHookProcess
{
    public $tag = false;

    /* (non-PHPdoc)
     * @see iPwGleanDoHookProcess::gleanData()
     */
    public function gleanData($value)
    {
        $this->tag = $value;
    }

    /* (non-PHPdoc)
     * @see iPwGleanDoHookProcess::run()
     */
    public function run($id)
    {
        if ($this->tag) {
            $data = app('weibo.PwWeibo')->getWeibo($id);
            if (!$data) return false;
            $tag = $this->tag;

            /* @var $logSrv PwLogService */
            $logSrv = app('log.srv.PwLogService');

            $langArgs = array();
            $langArgs['tag_url'] = url('tag/index/view', array('name' => $tag['tag_name']));
            $langArgs['tag'] = $tag['tag_name'];
            $langArgs['content_url'] = '';
            $langArgs['content'] = Tool::substrs(strip_tags(Tool::stripWindCode($data['content'])), 20, 0, true);
            $langArgs['type'] = '微博';

            $dm = new PwLogDm();
            $dm->setOperatedUser($data['created_userid'], $data['created_username']);
            //从话题中屏蔽帖子。管理日志添加
            $logSrv->addShieldTagLog($this->srv->user, $dm, $langArgs, $this->srv->ifShield);
        }
    }
}