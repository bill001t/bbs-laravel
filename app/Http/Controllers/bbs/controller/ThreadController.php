<?php

namespace App\Http\Controllers\bbs\controller;

use App\Core\Tool;
use App\Http\Controllers\Controller;
use App\Services\forum\bm\PwThreadList;
use App\Services\forum\bm\PwTopicTypeService;
use App\Services\forum\bm\threadList\PwCommonThread;
use App\Services\forum\bm\threadList\PwDigestThread;
use App\Services\forum\bm\threadList\PwNewForumThread;
use App\Services\forum\bm\threadList\PwSearchThread;
use App\Services\forum\bo\PwForumBo;
use App\Services\forum\bs\PwTopicType;
use Core;
use Illuminate\Http\Request;

/**
 * 帖子列表页
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: ThreadController.php 23994 2013-01-18 03:51:46Z long.shi $
 * @package forum
 */
class ThreadController extends Controller
{

    protected $topictypes;

    /**
     * 帖子列表页
     */
    public function run(Request $request)
    {
        $tab = $request->get('tab');
        $fid = intval($request->get('fid'));
        $type = intval($request->get('type', 'get')); //主题分类ID
        $page = $request->get('page', 'get');
        $orderby = $request->get('orderby', 'get');

        $pwforum = new PwForumBo($fid, true);
        if (!$pwforum->isForum()) {
            return $this->showError('BBS:forum.exists.not');
        }
        if ($pwforum->allowVisit($this->loginUser) !== true) {
            return $this->showError(array('BBS:forum.permissions.visit.allow', array('{grouptitle}' => $this->loginUser->getGroupInfo('name'))));
        }
        if (isset($pwforum->forumset['jumpurl'])) {
            return redirect($pwforum->forumset['jumpurl']);
        }
        if ($pwforum->foruminfo['password']) {
            if (!$this->loginUser->isExists()) {
                return redirect('u/login/run', array('backurl' => url('bbs/cate/run', array('fid' => $fid))));
            } elseif (Tool::getPwdCode($pwforum->foruminfo['password']) != Tool::getCookie('fp_' . $fid)) {
                return redirect('bbs/forum/password', array('fid' => $fid));
            }
        }
        $isBM = $pwforum->isBM($this->loginUser->username);
        if ($operateThread = $this->loginUser->getPermission('operate_thread', $isBM, array())) {
            $operateThread = Tool::subArray($operateThread, array('topped', 'digest', 'highlight', 'up', 'copy', 'type', 'move', /*'unite',*/
                'lock', 'down', 'delete', 'ban'));
        }
        $this->_initTopictypes($fid, $type);

        $threadList = new PwThreadList();
        $this->runHook('c_thread_run', $threadList);

        $threadList->setPage($page)
            ->setPerpage(isset($pwforum->forumset['threadperpage']) ? $pwforum->forumset['threadperpage'] : Core::C('bbs', 'thread.perpage'))
            ->setIconNew($pwforum->foruminfo['newtime']);

        $defaultOrderby = isset($pwforum->forumset['threadorderby']) ? 'postdate' : 'lastpost';
        !$orderby && $orderby = $defaultOrderby;

        if ($tab == 'digest') {
            $dataSource = new PwDigestThread($pwforum->fid, $type, $orderby);
        } elseif ($type) {
            $dataSource = new PwSearchThread($pwforum);
            $dataSource->setOrderby($orderby);
            $dataSource->setType($type, $this->_getSubTopictype($type));
        } elseif ($orderby == 'postdate') {
            $dataSource = new PwNewForumThread($pwforum);
        } else {
            $dataSource = new PwCommonThread($pwforum);
        }
        $orderby != $defaultOrderby && $dataSource->setUrlArg('orderby', $orderby);
        $threadList->execute($dataSource);
//dd($threadList->getList());
        return view('bbs.thread_run')
            ->with('threadList', $threadList)
            ->with('threaddb', $threadList->getList())
            ->with('fid', $fid)
            ->with('type', $type ? $type : null)
            ->with('tab', $tab)
            ->with('pwforum', $pwforum)
            ->with('headguide', $pwforum->headguide())
            ->with('icon', $threadList->icon)
            ->with('uploadIcon', $threadList->uploadIcon)
            ->with('operateThread', $operateThread)
            ->with('numofthreadtitle', isset($pwforum->forumset['numofthreadtitle']) ? $pwforum->forumset['numofthreadtitle'] : 26)
            ->with('postNeedLogin', (!$this->loginUser->uid && !$this->allowPost($pwforum)) ? ' J_qlogin_trigger' : '')
            ->with('defaultOrderby', $defaultOrderby)
            ->with('orderby', $orderby)
            ->with('urlargs', $threadList->getUrlArgs())
            ->with('topictypes', $this->_formatTopictype($type));


        /*->with($threadList->page, 'page');
        ->with($threadList->perpage, 'perpage');
        ->with($threadList->total, 'count');
        ->with($threadList->maxPage, 'totalpage');*/


        //版块风格
        /*if ($pwforum->foruminfo['style']) {
            $this->setTheme('forum', $pwforum->foruminfo['style']);
            //$this->addCompileDir($pwforum->foruminfo['style']);
        }*/

        //seo设置
        /*Wind::import('SRV:seo.bo.PwSeoBo');
        $seoBo = PwSeoBo::getInstance();
        $lang = Wind::getComponent('i18n');
        if ($threadList->page <=1) {
            if ($type)
                $seoBo->setDefaultSeo($lang->getMessage('SEO:bbs.thread.run.type.title'), '', $lang->getMessage('SEO:bbs.thread.run.type.description'));
            else
                $seoBo->setDefaultSeo($lang->getMessage('SEO:bbs.thread.run.title'), '', $lang->getMessage('SEO:bbs.thread.run.description'));
        }
        $seoBo->init('bbs', 'thread', $fid);
        $seoBo->set(array(
            '{forumname}' => $pwforum->foruminfo['name'],
            '{forumdescription}' => Tool::substrs($pwforum->foruminfo['descrip'], 100, 0, false),
            '{classification}' => $this->_getSubTopictypeName($type),
            '{page}' => $threadList->page
        ));
        Core::setV('seo', $seoBo);
        Tool::setCookie('visit_referer', 'fid_' . $fid . '_page_' . $threadList->page, 300);*/
    }

    private function _initTopictypes($fid, &$type)
    {
        $this->topictypes = $this->_getTopictypeService()->getTopicTypesByFid($fid);
        if (!isset($this->topictypes['all_types']) || !isset($this->topictypes['all_types'][$type])) $type = 0;
    }

    private function _getSubTopictype($type)
    {
        if (isset($this->topictypes['sub_topic_types']) && isset($this->topictypes['sub_topic_types'][$type])) {
            return array_keys($this->topictypes['sub_topic_types'][$type]);
        }
        return array();
    }

    private function _getSubTopictypeName($type)
    {
        return isset($this->topictypes['all_types'][$type]) ? $this->topictypes['all_types'][$type]['name'] : '';
    }

    private function _formatTopictype($type)
    {
        $topictypes = $this->topictypes;
        if (isset($topictypes['all_types']) && isset($topictypes['all_types'][$type]) && $topictypes['all_types'][$type]['parentid']) {
            $topictypeService = app(PwTopicTypeService::class);
            $topictypes = $topictypeService->sortTopictype($type, $topictypes);
        }
        return $topictypes;
    }

    private function _getTopictypeService()
    {
        return app(PwTopicType::class);
    }

    private function allowPost(PwForumBo $forum)
    {
        return $forum->foruminfo['allow_post'] ? $forum->allowPost($this->loginUser) : $this->loginUser->getPermission('allow_post');
    }
}