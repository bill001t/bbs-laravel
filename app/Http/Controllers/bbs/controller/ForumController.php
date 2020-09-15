<?php

namespace App\Http\Controllers\bbs\controller;

use App\Core\Tool;
use App\Http\Controllers\Controller;
use App\Services\forum\bm\PwForumService;
use App\Services\forum\bm\PwThreadList;
use App\Services\forum\bm\threadList\PwMyForumThread;
use App\Services\forum\bm\threadList\PwNewThread;
use App\Services\forum\bo\PwForumBo;
use App\Services\forum\bs\PwForum;
use App\Services\forum\bs\PwForumUser;
use App\Services\forum\bs\PwThreadIndex;
use App\Services\forum\bs\PwTopicType;
use App\Services\user\bs\PwUser;
use App\Services\user\dm\PwUserInfoDm;
use Core;
use Illuminate\Http\Request;

/**
 * 版块相关页面
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: ForumController.php 28803 2013-05-24 07:58:21Z jieyin $
 * @package forum
 */
class ForumController extends Controller
{

    public function run(Request $request)
    {
        $order = $request->get('order', 'get');
        $page = intval($request->get('page', 'get'));

        $threadList = new PwThreadList();
        // $this->runHook('c_thread_run', $forumDisplay);
        $threadList->setPage($page)->setPerpage(Core::C('bbs', 'thread.perpage'));

        $forbidFids = app(PwForumService::class)->getForbidVisitForum($this->loginUser);
        $dataSource = new PwNewThread($forbidFids);
        if ($order == 'postdate') {
            $dataSource->setOrderBy($order);
        } else {
            $dataSource->setOrderBy('lastpost');
        }
        $threadList->execute($dataSource);
        if ($threadList->total > 12000) {
            app(PwThreadIndex::class)->deleteOver($threadList->total - 10000);
        }
        $threaddb = $threadList->getList();
        $fids = array();
        foreach ($threaddb as $key => $value) {
            $fids[] = $value['fid'];
        }
        $forums = app(PwForumService::class)->fetchForum($fids);

        if ($operateThread = $this->loginUser->getPermission('operate_thread', false, array())) {
            $operateThread = Tool::subArray($operateThread, array('delete'));
        }

        return view('bbs.forum_run')
            ->with('threadList', $threaddb)
            ->with('forums', $forums)
            ->with('icon', $threadList->icon)
            ->with('uploadIcon', $threadList->uploadIcon)
            ->with('numofthreadtitle', 26)
            ->with('order', $order)
            ->with('operateThread', $operateThread);


        /*->with($threadList->page, 'page')
        ->with($threadList->perpage, 'perpage')
        ->with($threadList->total, 'count')
        ->with($threadList->maxPage, 'totalpage')
        ->with($threadList->getUrlArgs(), 'urlargs');*/

        // seo设置
        /*Wind::import('SRV:seo.bo.PwSeoBo');
        $seoBo = PwSeoBo::getInstance();
        $lang = Wind::getComponent('i18n');
        $threadList->page <=1 && $seoBo->setDefaultSeo($lang->getMessage('SEO:bbs.forum.run.title'), '', $lang->getMessage('SEO:bbs.forum.run.description'));
        $seoBo->init('bbs', 'new');
        $seoBo->set('{page}', $threadList->page);
        Core::setV('seo', $seoBo);*/
    }

    /**
     * 我的版块
     */
    public function myAction(Request $request)
    {
        /* if (!$this->loginUser->isExists()) {
             return redirect('u/login/run')->with('backurl', 'bbs/forum/my');
         }*/
        $order = $request->get('order');
        $page = intval($request->get('page'));

        $threadList = new PwThreadList();
        // $this->runHook('c_thread_run', $forumDisplay);
        $threadList->setPage($page)->setPerpage(Core::C('bbs', 'thread.perpage'));

        $dataSource = new PwMyForumThread($this->loginUser);
        if ($order == 'postdate') {
            $dataSource->setOrderBy($order);
        } else {
            $dataSource->setOrderBy('lastpost');
        }
        $threadList->execute($dataSource);
        $threaddb = $threadList->getList();
        $fids = array();
        foreach ($threaddb as $key => $value) {
            $fids[] = $value['fid'];
        }
        $forums = app(PwForum::class)->fetchForum($fids);

        return view('bbs.forum_my')
            ->with('threadList', $threaddb)
            ->with('forums', $forums)
            ->with('icon', $threadList->icon)
            ->with('uploadIcon', $threadList->uploadIcon)
            ->with('order', $order);


        /* ->with($threadList->page, 'page')
         ->with($threadList->perpage, 'perpage')
         ->with($threadList->total, 'count')
         ->with($threadList->maxPage, 'totalpage')
         ->with($threadList->getUrlArgs(), 'urlargs');*/

        // seo设置
        /*Wind::import('SRV:seo.bo.PwSeoBo');
        $seoBo = PwSeoBo::getInstance();
        $lang = Wind::getComponent('i18n');
        $seoBo->setCustomSeo($lang->getMessage('SEO:bbs.forum.my.title'), '', '');
        Core::setV('seo', $seoBo);*/
    }

    /**
     * 版块列表 弹窗
     */
    public function listAction(Request $request)
    {
        $withMyforum = $request->get('withMyforum');
        $service = app(PwForumService::class);
        $forums = $service->getForumList();
        $map = $service->getForumMap();
        $cate = array();
        $forum = array();
        foreach ($map[0] as $key => $value) {
            if (!$value['isshow']) continue;
            $array = $service->findOptionInMap($value['fid'], $map,
                array('forum' => '', 'sub' => '--', 'sub2' => '----'));
            $tmp = array();
            foreach ($array as $k => $v) {
                if ($forums[$k]['isshow'] && (!$forums[$k]['allow_post'] || $this->loginUser->inGroup(
                            explode(',', $forums[$k]['allow_post'])))
                ) {
                    $tmp[] = array($k, strip_tags($v));
                }
            }
            if ($tmp) {
                $cate[] = array($value['fid'], strip_tags($value['name']));
                $forum[$value['fid']] = $tmp;
            }
        }
        if ($withMyforum && $this->loginUser->isExists()
            && ($joinForum = app(PwForumUser::class)->getFroumByUid($this->loginUser->uid))
        ) {
            $tmp = array();
            foreach ($joinForum as $key => $value) {
                if (!$key) continue;
                $tmp[] = array($key, strip_tags($forums[$key]['name']));
            }
            array_unshift($cate, array('my', '我的版块'));
            $forum['my'] = $tmp;
        }
        $response = array('cate' => $cate, 'forum' => $forum);

        /* ->with($response, 'data');*///???????????
        return $this->showMessage('success');
    }

    /**
     * 加入版块
     */
    public function joinAction(Request $request)
    {
        $fid = $request->get('fid');

        if (!$fid) {
            return $this->showError('operate.fail');
        }

        $forum = new PwForumBo($fid);
        if (!$forum->isForum()) {
            return $this->showError('BBS:forum.exists.not');
        }
        if (!$this->loginUser->isExists()) {
            return $this->showError('login.not');
        }
        if (app(PwForumUser::class)->get($this->loginUser->uid, $fid)) {
            return $this->showError('BBS:forum.join.already');
        }

        app(PwForumUser::class)->join($this->loginUser->uid, $fid);

        $this->_addJoionForum($this->loginUser->info, $forum->foruminfo);

        return $this->showMessage('success');
    }

    /**
     * 退出版块
     */
    public function quitAction(Request $request)
    {
        $fid = $request->get('fid');

        if (!$fid) {
            return $this->showError('operate.fail');
        }

        $forum = new PwForumBo($fid);
        if (!$forum->isForum()) {
            return $this->showError('BBS:forum.exists.not');
        }
        if (!$this->loginUser->isExists()) {
            return $this->showError('login.not');
        }
        if (!app(PwForumUser::class)->get($this->loginUser->uid, $fid)) {
            return $this->showError('BBS:forum.join.not');
        }

        app(PwForumUser::class)->quit($this->loginUser->uid, $fid);

        $this->_removeJoionForum($this->loginUser->info, $fid);

        return $this->showMessage('success');
    }

    public function topictypeAction(Request $request)
    {
        $fid = $request->get('fid');
        $topictypes = app(PwTopicType::class)->getTopicTypesByFid($fid, !$this->loginUser->getPermission('operate_thread.type'));
        $data = array();
        foreach ($topictypes['topic_types'] as $key => $value) {
            $tmp = array('title' => strip_tags($value['name']), 'val' => $value['id']);
            if (isset($topictypes['sub_topic_types'][$value['id']])) {
                $sub = array();
                foreach ($topictypes['sub_topic_types'][$value['id']] as $k => $v) {
                    $sub[] = array('title' => strip_tags($v['name']), 'val' => $v['id']);
                }
                $tmp['items'] = $sub;
            }
            $data[] = $tmp;
        }
        /*->        with($data, 'data');*/
        return $this->showMessage('success');
    }

    /**
     * 进入版块的密码
     */
    public function passwordAction(Request $request)
    {
        $fid = $request->get('fid');

        return view('common.layout_error')
            ->with('fid', $fid)
            ->with('content', 'bbs.forum_password');
        /*$this->setLayout('TPL:common.layout_error');*/
    }

    /**
     * 验证版块密码
     */
    public function verifyAction(Request $request)
    {
        $fid = $request->get('fid');
        $password = $request->get('password', 'post');
        $forum = new PwForumBo($fid);
        if (!$forum->isForum(true)) {
            return $this->showError('BBS:forum.exists.not');
        }
        if (md5($password) != $forum->foruminfo['password']) {
            return $this->showError('BBS:forum.password.error');
        }
        Tool::setCookie('fp_' . $fid, Tool::getPwdCode(md5($password)), 86400);
        return $this->showMessage('success');
    }

    /**
     * 格式化数据  把字符串"1,版块1,2,版块2"格式化为数组
     *
     * @param string $string
     * @return array
     */
    public static function splitStringToArray($string)
    {
        $a = explode(',', $string);
        $l = count($a);
        $l % 2 == 1 && $l--;
        $r = array();
        for ($i = 0; $i < $l; $i += 2) {
            $r[$a[$i]] = $a[$i + 1];
        }
        return $r;
    }

    /**
     * 加入版块 - 更新我的版块缓存数据
     *
     * @param array $userInfo
     * @param array $foruminfo
     * @return boolean
     */
    private function _addJoionForum($userInfo, $foruminfo)
    {
        // 更新用户data表信息
        $array = array();
        $userInfo['join_forum'] && $array = self::splitStringToArray($userInfo['join_forum']);
        $array = array($foruminfo['fid'] => $foruminfo['name']) + $array;
        count($array) > 20 && $array = array_slice($array, 0, 20, true);

        $this->_updateMyForumCache($userInfo['uid'], $array);
        return true;
    }

    /**
     * 推出版块 - 更新我的版块缓存数据
     *
     * @param array $userInfo
     * @param int $fid
     * @return boolean
     */
    private function _removeJoionForum($userInfo, $fid)
    {
        // 更新用户data表信息
        if (!empty($userInfo['join_forum'])) {
            $array = self::splitStringToArray($userInfo['join_forum']);
            unset($array[$fid]);

            $this->_updateMyForumCache($userInfo['uid'], $array);
        }

        return true;
    }

    private function _updateMyForumCache($uid, $array)
    {
        $joinForums = app(PwForumService::class)->getJoinForum($uid);
        $_tmpArray = array();

        foreach ($array as $k => $v) {
            if (!isset($joinForums[$k])) continue;
            $_tmpArray[$k] = strip_tags($joinForums[$k]);
        }

        $dm = new PwUserInfoDm($uid);
        $dm->setJoinForum(self::_formatJoinForum($_tmpArray));
        return $this->_getUserDs()->editUser($dm, PwUser::FETCH_DATA);
    }

    /**
     * 格式化我的版块缓存数据结构
     *
     * @param array $array 格式化成"1,版块1,2,版块2"
     * @return string
     */
    private static function _formatJoinForum($array)
    {
        if (!$array) return false;
        $myForum = $user = '';
        foreach ($array as $fid => $name) {
            $myForum .= $fid . ',' . $name . ',';
        }
        return rtrim($myForum, ',');
    }

    /**
     * @return PwUser
     */
    private function _getUserDs()
    {
        return app(PwUser::class);
    }

    /**
     * @return PwForum
     */
    private function _getForumService()
    {
        return app(PwForum::class);
    }
}