<?php

namespace App\Services\forum\bm\post;

use App\Core\Tool;
use App\Core\ErrorBag;
use App\Services\forum\bs\PwThread;
use App\Services\forum\bm\PwThreadType;
use App\Services\forum\dm\PwTopicDm;
use App\Services\forum\dm\PwPostDm;

/**
 * 帖子发布相关服务
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwTopicPost.php 28950 2013-05-31 05:58:25Z jieyin $
 * @package forum
 */
class PwTopicPost extends PwPostAction
{

    protected $tid;
    protected $special = 'default';

    /**
     * @see PwPostAction.isInit
     */
    public function isInit()
    {
        return true;
    }

    public function setSpecial($special)
    {
        $this->special = app(PwThreadType::class)->has($special) ? $special : 'default';
    }

    public function getSpecial()
    {
        return $this->special;
    }

    /**
     * @see PwPostAction.check
     */
    public function check()
    {
        if (isset($this->forum->forumset['allowtype']) && !in_array($this->special, $this->forum->forumset['allowtype'])) {
            return new ErrorBag('BBS:post.forum.allow.ttype', array('{ttype}' => app('forum.srv.PwThreadType')->getName($this->special)));
        }
        if (($result = $this->forum->allowPost($this->user)) !== true) {
            return new ErrorBag('BBS:forum.permissions.post.allow', array('{grouptitle}' => $this->user->getGroupInfo('name')));
        }
        if (!empty($this->forum->foruminfo['allow_post']) && !empty($this->user->getPermission('allow_post'))) {
            return new ErrorBag('permission.post.allow', array('grouptitle' => $this->user->getGroupInfo('name')));
        }
        if (($result = $this->checkPostNum()) !== true) {
            return $result;
        }
        if (($result = $this->checkPostPertime()) !== true) {
            return $result;
        }
        return true;
    }

    /**
     * @see PwPostAction.getDm
     */
    public function getDm()
    {
        return new PwTopicDm(0, $this->forum, $this->user);
    }

    /**
     * @see PwPostAction.getInfo
     */
    public function getInfo()
    {
        return array();
    }

    /**
     * @see PwPostAction.getAttachs
     */
    public function getAttachs()
    {
        return array();
    }

    /**
     * @see PwPostAction.dataProcessing
     */
    public function dataProcessing(PwPostDm $postDm)
    {
        $time = Tool::getTime();
        $postDm->setFid($this->forum->fid)
            ->setAuthor($this->user->uid, $this->user->username, $this->user->ip)
            ->setCreatedTime($time)
            ->setDisabled($this->isDisabled())
            ->setLastpost($this->user->uid, $this->user->username, $time);

        if (($result = $this->checkContentHash($postDm->getContent())) !== true) {
            return $result;
        }
        if (($result = $this->checkTopictype($postDm)) !== true) {
            return $result;
        }
        if (($postDm = $this->runWithFilters('dataProcessing', $postDm)) instanceof ErrorBag) {
            return $postDm;
        }
        $this->postDm = $postDm;
        return true;
    }

    /**
     * @see PwPostAction.execute
     */
    public function execute()
    {
        $result = $this->_getThreadService()->addThread($this->postDm);
        if ($result instanceof ErrorBag) {
            return $result;
        }
        $this->tid = $result;
        $this->afterPost();
        return true;
    }

    /**
     * 发帖后续操作<更新版块、缓存等信息>
     */
    public function afterPost()
    {
        if ($this->postDm->getIscheck()) {
            $this->forum->addThread($this->tid, $this->user->username, $this->postDm->getTitle());
        }
    }

    /**
     * @see PwPostAction.afterRun
     */
    public function afterRun()
    {
        $this->runDo('addThread', $this->tid);
    }

    public function getCreditOperate()
    {
        return 'post_topic';
    }

    public function isForumContentCheck()
    {
        return (intval($this->forum->forumset['contentcheck']) & 1);
    }

    public function updateUser()
    {
        $userDm = parent::updateUser();
        $userDm->addPostnum(1)->addTodaypost(1)->setPostcheck($this->getHash($this->postDm->getContent()));
        return $userDm;
    }

    public function getNewId()
    {
        return $this->tid;
    }

    protected function _getThreadService()
    {
        return app(PwThread::class);
    }
}