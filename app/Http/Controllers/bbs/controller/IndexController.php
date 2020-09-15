<?php

namespace App\Http\Controllers\bbs\controller;

use App\Core\Tool;
use App\Core\MessageTool;
use App\Http\Controllers\Controller;
use App\Services\forum\bm\PwForumService;
use App\Services\forum\bm\PwThreadList;
use App\Services\forum\bm\threadList\PwNewThread;
use App\Services\forum\bs\PwThreadIndex;
use App\Services\seo\bo\PwSeoBo;
use Core;
use Illuminate\Http\Request;

/**
 * 默认站点首页
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: IndexController.php 24758 2013-02-20 06:55:42Z jieyin $
 * @package forum
 */
class IndexController extends Controller
{

    public function run(Request $request)
    {
        $order = $request->get('order', 'get');
        $page = intval($request->get('page', 'get'));

        $threadList = new PwThreadList();
        $this->runHook('c_index_run', $threadList);

        $threadList->setPage($page)->setPerpage(Core::C('bbs', 'thread.perpage'));

        $forbidFids = app(PwForumService::class)->getForbidVisitForum($this->loginUser, null, true);
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

        return view('bbs.index_run')
            ->with( 'threadList', $threadList)
            ->with( 'threaddb', $threaddb)
            ->with( 'forums', $forums)
            ->with( 'icon', $threadList->icon)
            ->with('uploadIcon', $threadList->uploadIcon)
            ->with('numofthreadtitle', 26)
            ->with('order', $order)
            ->with('operateThread', $operateThread)
            ->with('page', $threadList->page)
            ->with('perpage', $threadList->perpage)
            ->with('count', $threadList->total)
            ->with('totalpage', $threadList->maxPage)
            ->with('urlargs', $threadList->getUrlArgs());

        // seo设置
        /*$seoBo = PwSeoBo::getInstance();
        $lang = app(MessageTool::class);
        $threadList->page <= 1 && $seoBo->setDefaultSeo($lang->getMessage('SEO:bbs.forum.run.title'), '', $lang->getMessage('SEO:bbs.forum.run.description'));
        $seoBo->init('bbs', 'new');
        $seoBo->set('{page}', $threadList->page);
        Core::setV('seo', $seoBo);*/
    }
}
