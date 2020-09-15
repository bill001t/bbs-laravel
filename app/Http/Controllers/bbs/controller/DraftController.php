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
 * 草稿箱
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class DraftController extends Controller{
	private $maxNum = 10;
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if ($this->loginUser->uid < 1) {
			return $this->showError('BBS:draft.user.not.login');
		}
	}
	
	/**
	 * 添加草稿
	 *
	 * @return void
	 */
	public function doAddAction(Request $request) {
		list($title,$content) = $request->get(array('atc_title','atc_content'), 'post');
		if (!$title || !$content) {
			return $this->showError('BBS:draft.content.empty');
		}
		if ($this->_getDraftDs()->countByUid($this->loginUser->uid) >= $this->maxNum) {
			return $this->showError('BBS:draft.num.max');
		}
		$draftDm = new PwDraftDm();
		$draftDm->setTitle($title)
				->setContent($content)
				->setCreatedUserid($this->loginUser->uid)
				->setCreatedTime(PW::getTime());
		$this->_getDraftDs()->addDraft($draftDm);
		return $this->showMessage('success');
	}
		
	/**
	 * do删除
	 *
	 * @return void
	 */
	public function doDeleteAction(Request $request) {
		$id = (int)$request->get('id', 'post');
		if (!$id) {
			return $this->showError('operate.fail');
		}

		$draft = $this->_getDraftDs()->getDraft($id);
		if ($draft['created_userid'] != $this->loginUser->uid) {
			return $this->showError('BBS:draft.operater.error');
		}
		$this->_getDraftDs()->deleteDraft($id,$this->loginUser->uid);
		return $this->showMessage('success');
	}
	
	/**
	 * 发帖页我的草稿
	 *
	 * @return void
	 */
	public function myDraftsAction(Request $request) {
		$drafts = $this->_getDraftDs()->getByUid($this->loginUser->uid,$this->maxNum);
		$data = array();
		foreach ($drafts as $v) {
			$_tmp['id'] = $v['id'];
			$_tmp['title'] = $v['title'];
			$_tmp['content'] = $v['content'];
			$_tmp['created_time'] = Tool::time2str($v['created_time'],'auto');
			$data[] = $_tmp;
		}
		Tool::echoJson(array('state' => 'success', 'data' => $data));exit;
	}
	
	/**
	 * 草稿DS
	 * 
	 * @return PwDraft
	 */
	protected function _getDraftDs() {
		return app('draft.PwDraft');
	}
}
?>