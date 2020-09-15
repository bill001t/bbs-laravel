<?php

namespace App\Http\Controllers\bbs\controller;

use App\Core\Hook\SimpleHook;
use App\Core\Tool;
use App\Http\Controllers\Controller;
use App\Services\credit\bo\PwCreditBo;
use App\Services\forum\bm\post\PwReplyModify;
use App\Services\forum\bm\post\PwReplyPost;
use App\Services\forum\bm\post\PwTopicModify;
use App\Services\forum\bm\post\PwTopicPost;
use App\Services\forum\bm\PwPost;
use App\Services\forum\bs\PwTopicType;
use Core;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Requests;
use Route;

/**
 * 发帖
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: PostController.php 27729 2013-04-28 02:00:50Z jieyin $
 * @package forum
 */
class PostController extends Controller
{

    public $post;

    private function beforeAction($viewname = '')
    {
        $currentRoute = Route::currentRouteName();
        $action = trim(mb_strrichr($currentRoute, '.'), '.');

        if (in_array($action, array('fastreply', 'replylist'))) {
            return true;
        }

        $this->post = $this->_getPost($action);

        if (($result = $this->post->check()) !== true) {
            $error = $result->getError();
            if (is_array($error) && $error[0] == 'BBS:post.forum.allow.ttype'
                && ($allow = $this->post->forum->getThreadType($this->post->user))
            ) {
                $special = key($allow);
                return redirect('bbs/post/run?fid=' . $this->post->forum->fid . ($special ? ('&special=' . $special) : ''));
            }

            return $this->showError($error);
        }

        //版块风格
        $pwforum = $this->post->forum;
        if (!empty($pwforum->foruminfo['password'])) {
            if (!$this->loginUser->isExists()) {
                return redirect('u/login/run')->withInput(['backurl' => route($currentRoute), 'fid' => $pwforum->fid]);
            } elseif (Tool::getPwdCode($pwforum->foruminfo['password']) != Tool::getCookie('fp_' . $pwforum->fid)) {
                return redirect('bbs/forum/password')->withInput(['fid' => $pwforum->fid]);
            }
        }

        if (!empty($viewname)) {
            $args = ['action' => $action];
            view()->composer($viewname, function ($view) use ($args) {
                $view->with($args);
            });
        }

        return true;
        /*if ($pwforum->foruminfo['style']) {
            $this->setTheme('forum', $pwforum->foruminfo['style']);
        }*/

        /*->with($action, 'action');*/
    }

    /**
     * 发帖页
     */
    public function run()
    {
        $viewname = 'bbs.post_run';

        if($this->beforeAction($viewname) !== true){
            return $this->beforeAction($viewname);
        }

        $this->runHook('c_post_run', $this->post);

        $this->_initTopictypes($viewname, 0);
        $this->_initVar($viewname);

        return view($viewname)
            ->with('do', 'doadd')
            ->with('special', $this->post->special)
            ->with('reply_notice', 'checked')
            ->with('headguide', $this->post->forum->headguide())
            ->with('hasVerifyCode', in_array('postthread', (array)Core::C('verify', 'showverify')));


        // seo设置
        /*Wind::import('SRV:seo.bo.PwSeoBo');
        $seoBo = PwSeoBo::getInstance();
        $lang = Wind::getComponent('i18n');
        $seoBo->setCustomSeo($lang->getMessage('SEO:bbs.post.run.title'), '', '');
        Core::setV('seo', $seoBo);*/
    }

    /**
     * 发帖
     */
    public function doaddAction(Request $request)
    {
        $this->beforeAction();

        list($title, $content, $topictype, $subtopictype, $reply_notice, $hide) = array_values
        ($request->only('atc_title', 'atc_content', 'topictype', 'sub_topictype', 'reply_notice', 'hide'));

        $pwPost = $this->post;
        $this->runHook('c_post_doadd', $pwPost);

        $postDm = $pwPost->getDm();
        $postDm->setTitle($title)
            ->setContent($content)
            ->setHide($hide)
            ->setReplyNotice($reply_notice);

        //set topic type
        $topictype_id = $subtopictype ? $subtopictype : $topictype;
        $topictype_id && $postDm->setTopictype($topictype_id);

        if (($result = $pwPost->execute($postDm)) !== true) {
            $data = $result->getData();
            $data && $this->addMessage($data, 'data');
            return $this->showError($result->getError());
        }
        $tid = $pwPost->getNewId();

        return $this->showMessage('success', 'bbs/read/run/?tid=' . $tid . '&fid=' . $pwPost->forum->fid, true);
    }

    /**
     * 发回复页
     */
    public function replyAction(Request $request)
    {
        $pid = $request->get('pid');
        $this->runHook('c_post_reply', $this->post);

        $info = $this->post->getInfo();

        return view('bbs.post_run')
            ->with('', 'atc_title')
            ->with('default_title', 'Re:' . $info['subject'])
            ->with('do', 'doreply')
            ->with('tid', $info['tid'])
            ->with('pid', $pid)
            ->with('reply_notice', 'checked')
            ->with('headguide', $this->post->forum->headguide() . $this->post->forum->bulidGuide(array($info['subject'], url('bbs/read/run', array('tid' => $info['tid'], 'fid' => $this->post->forum->fid)))));
        $this->_initVar();


        // seo设置
        /*Wind::import('SRV:seo.bo.PwSeoBo');
        $seoBo = PwSeoBo::getInstance();
        $lang = Wind::getComponent('i18n');
        $seoBo->setCustomSeo($lang->getMessage('SEO:bbs.post.reply.title'), '', '');
        Core::setV('seo', $seoBo);*/
    }

    /**
     * 快速回复
     */
    public function fastreplyAction(Request $request)
    {
        $this->_replylist();
    }

    /**
     * 回复列表
     */
    public function replylistAction(Request $request)
    {
        $this->_replylist();
    }

    /**
     * 回复
     */
    public function doreplyAction(Request $request)
    {
        $tid = $request->get('tid');
        list($title, $content, $hide, $rpid) = $request->get(array('atc_title', 'atc_content', 'hide', 'pid'), 'post');
        $_getHtml = $request->get('_getHtml', 'get');
        $pwPost = $this->post;
        $this->runHook('c_post_doreply', $pwPost);

        $info = $pwPost->getInfo();
        $title == 'Re:' . $info['subject'] && $title = '';
        if ($rpid) {
            $post = app('thread.PwThread')->getPost($rpid);
            if ($post && $post['tid'] == $tid && $post['ischeck']) {
                $post['content'] = $post['ifshield'] ? '此帖已被屏蔽' : trim(Tool::stripWindCode(preg_replace('/\[quote(=.+?\,\d+)?\].*?\[\/quote\]/is', '', $post['content'])));
                $post['content'] && $content = '[quote=' . $post['created_username'] . ',' . $rpid . ']' . Tool::substrs($post['content'], 120) . '[/quote] ' . $content;
            } else {
                $rpid = 0;
            }
        }

        $postDm = $pwPost->getDm();
        $postDm->setTitle($title)
            ->setContent($content)
            ->setHide($hide)
            ->setReplyPid($rpid);

        if (($result = $pwPost->execute($postDm)) !== true) {
            $data = $result->getData();
            $data && $this->addMessage($data, 'data');
            return $this->showError($result->getError());
        }
        $pid = $pwPost->getNewId();

        if ($_getHtml == 1) {
            Wind::import('SRV:forum.srv.threadDisplay.PwReplyRead');
            Wind::import('SRV:forum.srv.PwThreadDisplay');
            $threadDisplay = new PwThreadDisplay($tid, $this->loginUser);
            $this->runHook('c_post_replyread', $threadDisplay);
            $dataSource = new PwReplyRead($tid, $pid);
            $threadDisplay->execute($dataSource);
            $_cache = Core::cache()->fetch(array('level', 'group_right'));


            return view('read_floor')
                ->with('threadDisplay', $threadDisplay)
                ->with($tid, 'tid')
                ->with('fid', $threadDisplay->fid)
                ->with('threadInfo', $threadDisplay->getThreadInfo())
                ->with('read', current($threadDisplay->getList()))
                ->with('users', $threadDisplay->getUsers())
                ->with('area', $threadDisplay->getArea())
                ->with('pwforum', $threadDisplay->getForum())
                ->with('creditBo', PwCreditBo::getInstance())
                ->with('displayMemberInfo', Core::C('bbs', 'read.display_member_info'))
                ->with('displayInfo', Core::C('bbs', 'read.display_info'))
                ->with('ltitle', $_cache['level']['ltitle'])
                ->with('lpic', $_cache['level']['lpic'])
                ->with('lneed', $_cache['level']['lneed'])
                ->with('groupRight', $_cache['group_right']);


        } elseif ($_getHtml == 2) {
            $content = app('forum.srv.PwThreadService')->displayContent($content, $postDm->getField('useubb'), $postDm->getField('reminds'));

            return view('read_reply_floor')
                ->with('ischeck', $postDm->getField('ischeck'))
                ->with('content', $content)
                ->with('uid', $this->loginUser->uid)
                ->with('username', $this->loginUser->username)
                ->with('pid', $pid)
                ->with('time', Tool::getTime() - 1);

        } else {
            return $this->showMessage('success', 'bbs/read/run/?tid=' . $tid . '&fid=' . $pwPost->forum->fid . '&page=e#' . $pid, true);
        }
    }

    /**
     * 帖子编辑页
     */
    public function modifyAction(Request $request)
    {
        $tid = $request->get('tid');
        $this->runHook('c_post_modify', $this->post);
        $info = $this->post->getInfo();


        if ($this->post->action instanceof PwTopicModify) {
            $this->_initTopictypes($info['topic_type']);
            $headtitle = $info['subject'];
        } else {
            $thread = app('forum.PwThread')->getThread($info['tid']);
            $headtitle = $thread['subject'];
        }

        if (isset($info['reply_notice'])) {
            view('post_run')->with('reply_notice', 'checked');
        }

        return view('post_run')
            ->with('atc_title', $info['subject'])
            ->with('atc_content', $info['content'])
            ->with('do', 'domodify')
            ->with('tid', $info['tid'])
            ->with('pid', $request->get('pid'))
            ->with('attach', $this->_bulidAttachs($this->post->getAttachs()))
            ->with('special', $this->post->special)
            ->with('headguide', $this->post->forum->headguide() . $this->post->forum->bulidGuide(array($headtitle, url('bbs/read/run', array('tid' => $info['tid'], 'fid' => $this->post->forum->fid)))));


        $this->_initVar();


        // seo设置
        /*	Wind::import('SRV:seo.bo.PwSeoBo');
            $seoBo = PwSeoBo::getInstance();
            $lang = Wind::getComponent('i18n');
            $seoBo->setCustomSeo($lang->getMessage('SEO:bbs.post.modify.title'), '', '');
            Core::setV('seo', $seoBo);*/
    }

    /**
     * 编辑帖子
     */
    public function domodifyAction(Request $request)
    {
        $tid = $request->get('tid');
        $pid = $request->get('pid');
        list($title, $content, $topictype, $subtopictype, $reply_notice, $hide) = $request->get(array('atc_title', 'atc_content', 'topictype', 'sub_topictype', 'reply_notice', 'hide'), 'post');
        $pwPost = $this->post;
        $this->runHook('c_post_domodify', $pwPost);

        $postDm = $pwPost->getDm();
        $postDm->setTitle($title)
            ->setContent($content)
            ->setHide($hide)
            ->setReplyNotice($reply_notice);

        //set topic type
        $topictype_id = $subtopictype ? $subtopictype : $topictype;
        $topictype_id && $postDm->setTopictype($topictype_id);

        if (($result = $pwPost->execute($postDm)) !== true) {
            $data = $result->getData();
            $data && $this->addMessage($data, 'data');
            return $this->showError($result->getError());
        }
        return $this->showMessage('success', 'bbs/read/jump/?tid=' . $tid . '&pid=' . $pid, true);
    }

    private function _getPost($routename)
    {
        switch ($routename) {
            case 'reply':
            case 'doreply':
                $tid = Requests::input('tid');
                $postAction = new PwReplyPost($tid);
                break;
            case 'modify':
            case 'domodify':
                $tid = Requests::input('tid');
                $pid = Requests::input('pid');
                if ($pid) {
                    $postAction = new PwReplyModify($pid);
                } else {
                    $postAction = new PwTopicModify($tid);
                }
                break;
            default:
                $fid = Requests::input('fid');
                $special = Requests::input('special');
                $postAction = new PwTopicPost($fid);
                $special && $postAction->setSpecial($special);
        }
        return new PwPost($postAction);
    }

    private function _replylist()
    {
        list($tid, $pid, $page) = Requests::input(array('tid', 'pid', 'page'), 'get');

        $page = intval($page);
        $page < 1 && $page = 1;
        $perpage = 10;

        $info = app('forum.PwThread')->getThread($tid);
        $replydb = array();
        if ($pid) {
            $reply = app('forum.PwThread')->getPost($pid);
            $total = $reply['replies'];
            list($start, $limit) = Tool::page2limit($page, $perpage);
            /*Wind::import('LIB:ubb.PwSimpleUbbCode');
            Wind::import('LIB:ubb.config.PwUbbCodeConvertThread');*/
            $replydb = app(PwPostsReply::class)->getPostByPid($pid, $limit, $start);
            $replydb = app(PwThreadService::class)->displayReplylist($replydb);
        } else {
            $total = 0;
        }


        /*->with($page, 'page');
        ->with($perpage, 'perpage');
        ->with($total, 'count');

        ->with($pid, 'pid');
        ->with($replydb, 'replydb');
        ->with($info['tid'], 'tid');*/
    }

    private function _initVar($viewname)
    {
        $creditBo = PwCreditBo::getInstance();
        $sellCreditRange = $this->loginUser->getPermission('sell_credit_range', false, array());
        $allowThreadExtend = $this->loginUser->getPermission('allow_thread_extend', false, array());
        $sellConfig = array(
            'ifopen' => (isset($this->post->forum->forumset['allowsell']) && isset($allowThreadExtend['sell'])) ? 1 : 0,
            'price' => isset($sellCreditRange['maxprice']) ? $sellCreditRange['maxprice'] : '',
            'income' => isset($sellCreditRange['maxincome']) ? $sellCreditRange['maxincome'] : '',
            'credit' => Tool::subArray($creditBo->cType, $this->loginUser->getPermission('sell_credits'))
        );
        !$sellConfig['credit'] && $sellConfig['credit'] = array_slice($creditBo->cType, 0, 1, true);

        $enhideConfig = array(
            'ifopen' => (isset($this->post->forum->forumset['allowhide']) && isset($allowThreadExtend['hide'])) ? 1 : 0,
            'credit' => Tool::subArray($creditBo->cType, $this->loginUser->getPermission('enhide_credits'))
        );
        !isset($enhideConfig['credit']) && $enhideConfig['credit'] = array_slice($creditBo->cType, 0, 1, true);

        $allowUpload = ($this->post->user->isExists() && $this->post->forum->allowUpload($this->post->user) && ($this->post->user->getPermission('allow_upload') || $this->post->forum->foruminfo['allow_upload'])) ? 1 : 0;
        $attachnum = intval(Core::C('attachment', 'attachnum'));
        if ($perday = $this->post->user->getPermission('uploads_perday')) {
            $count = $this->post->user->info['lastpost'] < Tool::getTdtime() ? 0 : $this->post->user->info['todayupload'];
            $attachnum = max(min($attachnum, $perday - $count), 0);
        }

        $args = [
            'editor_app_config' => SimpleHook::getInstance('PwEditor_app')->runWithFilters(array()),
            'pwpost' => $this->post,
            'needcheck' => $this->post->getDisabled(),
            'fid' => $this->post->forum->fid,
            'pwforum' => $this->post->forum,
            'sellConfig' => $sellConfig,
            'enhideConfig' => $enhideConfig,
            'allowThreadExtend' => $allowThreadExtend,
            'allowUpload' => $allowUpload,
            'attachnum' => $attachnum,
        ];

        view()->composer($viewname, function ($view) use ($args) {
            $view->with($args);
        });

        /*->with(SimpleHook::getInstance('PwEditor_app')->runWithFilters(array()), 'editor_app_config');
        ->with($this->post, 'pwpost');
        ->with($this->post->getDisabled(), 'needcheck');
        ->with($this->post->forum->fid, 'fid');
        ->with($this->post->forum, 'pwforum');
        ->with($sellConfig, 'sellConfig');
        ->with($enhideConfig, 'enhideConfig');
        ->with($allowThreadExtend, 'allowThreadExtend');
        ->with($allowUpload, 'allowUpload');
        ->with($attachnum, 'attachnum');*/
    }

    private function _bulidAttachs($attach)
    {
        if (!$attach) return '';
        $array = array();
        ksort($attach);
        reset($attach);
        foreach ($attach as $key => $value) {
            $array[$key] = array(
                'name' => $value['name'],
                'size' => $value['size'],
                'path' => Tool::getPath($value['path'], $value['ifthumb'] & 1),
                'thumbpath' => Tool::getPath($value['path'], $value['ifthumb']),
                'desc' => $value['descrip'],
                'special' => $value['special'],
                'cost' => $value['cost'],
                'ctype' => $value['ctype']
            );
        }
        return $array;
    }

    private function _initTopictypes($viewname, $defaultTopicType = 0)
    {
        $topictypes = $jsonArray = array();
        $forceTopicType = isset($this->post->forum->forumset['force_topic_type']) ? 1 : 0;
        if (isset($this->post->forum->forumset['topic_type'])) {
            $permission = $this->loginUser->getPermission('operate_thread', false, array());
            $topictypes = $this->_getTopictypeDs()->getTopicTypesByFid($this->post->forum->fid, !$permission['type']);
            foreach ($topictypes['sub_topic_types'] as $key => $value) {
                if (!is_array($value)) continue;
// 				if (!$forceTopicType && $value) $jsonArray[$key][$key] = '无分类';
                foreach ($value as $k => $v) {
                    $jsonArray[$key][$k] = strip_tags($v['name']);
                }
            }
        }
        if ($defaultTopicType && isset($topictypes['all_types'][$defaultTopicType])) {
            $defaultParentTopicType = $topictypes['all_types'][$defaultTopicType]['parentid'];
        } else {
            $defaultTopicType = $defaultParentTopicType = 0;
        }
        $json = Tool::jsonEncode($jsonArray);


        /*->with($defaultTopicType, 'defaultTopicType');
    ->with($defaultParentTopicType, 'defaultParentTopicType');
    ->with($topictypes, 'topictypes');
    ->with($json, 'subTopicTypesJson');
    ->with($forceTopicType ? 1 : 0, 'forceTopic');
    ->with('1', 'isTopic');*/

        $args = [
            'defaultTopicType' => $defaultTopicType,
            'defaultParentTopicType' => $defaultParentTopicType,
            'topictypes' => $topictypes,
            'subTopicTypesJson' => $json,
            'forceTopic' => $forceTopicType,
            'isTopic' => 1,
        ];

        view()->composer($viewname, function ($view) use ($args) {
            $view->with($args);
        });
    }

    /**
     *
     * Enter description here ...
     * @return PwTopicType
     */
    private function _getTopictypeDs()
    {
        return app(PwTopicType::class);
    }

}