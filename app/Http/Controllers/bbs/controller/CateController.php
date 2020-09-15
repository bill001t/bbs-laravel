<?php

namespace App\Http\Controllers\bbs\controller;

use App\Core\Tool;
use App\Http\Controllers\Controller;
use App\Services\forum\bm\PwForumService;
use App\Services\forum\bm\PwThreadList;
use App\Services\forum\bm\PwTopicTypeService;
use App\Services\forum\bm\threadList\PwCateDigestThread;
use App\Services\forum\bm\threadList\PwCateThread;
use App\Services\forum\bo\PwForumBo;
use App\Services\forum\bs\PwThreadCateIndex;
use Core;
use Illuminate\Http\Request;

/**
 * 分类页面
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: CateController.php 28799 2013-05-24 06:47:37Z yetianshi $
 * @package forum
 */
class CateController extends Controller
{

    /* (non-PHPdoc)
     * @see WindController::run()
     */
    public function run(Request $request)
    {
        $fid = intval($request->get('fid'));
        $pwforum = new PwForumBo($fid, true);
        if (!$pwforum->isForum(true)) {
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
            $operateThread = Tool::subArray($operateThread, array('delete'));
        }
        $pwforum->foruminfo['threads'] = $pwforum->foruminfo['subthreads'];


        $tab = $request->get('tab');//tab标签
        $page = intval($request->get('page', 'get'));
        $orderby = $request->get('orderby', 'get');

        $threadList = new PwThreadList();
        $this->runHook('c_cate_run', $threadList);

        $threadList->setPage($page)
            ->setPerpage(isset($pwforum->forumset['threadperpage']) ? $pwforum->forumset['threadperpage'] : Core::C('bbs', 'thread.perpage'))
            ->setIconNew($pwforum->foruminfo['newtime']);

        $defaultOrderby = isset($pwforum->forumset['threadorderby']) ? 'postdate' : 'lastpost';
        !$orderby && $orderby = $defaultOrderby;

        $isCommon = 0;
        if ($tab == 'digest') {
            $dataSource = new PwCateDigestThread($pwforum->fid, $orderby);
        } else {
            $srv = app(PwForumService::class);
            $forbidFids = $srv->getForbidVisitForum($this->loginUser, $srv->getForumsByLevel($fid, $srv->getForumMap()), true);
            $dataSource = new PwCateThread($pwforum, $forbidFids);
            $dataSource->setOrderby($orderby);
            $isCommon = 1;
        }
        $orderby != $defaultOrderby && $dataSource->setUrlArg('orderby', $orderby);
        $threadList->execute($dataSource);
        if ($isCommon && $threadList->total > 12000) {
            app(PwThreadCateIndex::class)->deleteOver($fid, $threadList->total - 10000);
        }

        return view('bbs.cate_run')
            ->with('threadList', $threadList)
            ->with('threaddb', $threadList->getList())
            ->with('tab', $tab)
            ->with('defaultOrderby', $defaultOrderby)
            ->with('orderby', $orderby)
            ->with('fid', $pwforum->fid)
            ->with('pwforum', $pwforum)
            ->with('headguide', $pwforum->headguide())
            ->with('icon', $threadList->icon)
            ->with('uploadIcon', $threadList->uploadIcon)
            ->with('numofthreadtitle', isset($pwforum->forumset['numofthreadtitle']) ? $pwforum->forumset['numofthreadtitle'] : 26)
            ->with('operateThread', $operateThread);

        /*->with($threadList->page, 'page');
        ->with($threadList->perpage, 'perpage');
        ->with($threadList->total, 'count');
        ->with($threadList->maxPage, 'totalpage');
        ->with($threadList->getUrlArgs(), 'urlargs');*/

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
            $seoBo->setDefaultSeo($lang->getMessage('SEO:bbs.thread.run.title'), '', $lang->getMessage('SEO:bbs.thread.run.description'));
        }
        $seoBo->init('bbs', 'thread', $fid);
        $seoBo->set(array(
            '{forumname}' => $pwforum->foruminfo['name'],
            '{forumdescription}' => Tool::substrs($pwforum->foruminfo['descrip'], 100, 0, false),
            '{classification}' => '',
            '{page}' => $threadList->page
        ));
        Core::setV('seo', $seoBo);*/
    }

    /**
     * 主题分类
     */
    public function topictypesAction(Request $request)
    {
        $fid = (int)$request->get('fid');
        if ($fid < 1) {
            return $this->showError('data.error');
        }
        $topicTypes = app(PwTopicTypeService::class)->getTopictypes($fid);
        $topicTypes = $topicTypes ? $topicTypes : '';
        Tool::echoJson(array('state' => 'success', 'data' => $topicTypes));
        exit;
    }
}