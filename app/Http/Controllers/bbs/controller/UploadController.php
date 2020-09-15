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
class UploadController extends Controller{

	public function run() {

		header("Content-type: text/html; charset=" . Core::V('charset'));
		//$pwServer['HTTP_USER_AGENT'] = 'Shockwave Flash';
		$swfhash = 1/*GetVerify($winduid)*/;
		Tool::echoJson(array('uid' => $this->loginUser->uid, 'a' => 'dorun', 'verify' => $swfhash));
		
		return view('');

	}

	public function dorunAction(Request $request) {
		
		if (!$user = $this->_getUser()) {
			return $this->showError('login.not');
		}
		$fid = $request->get('fid', 'post');

		Wind::import('SRV:upload.action.PwAttMultiUpload');
		Wind::import('LIB:upload.PwUpload');
		$bhv = new PwAttMultiUpload($user, $fid);

		$upload = new PwUpload($bhv);
		if (($result = $upload->check()) === true) {
			$result = $upload->execute();
		}
		if ($result !== true) {
			return $this->showError($result->getError());
		}
		if (!$data = $bhv->getAttachInfo()) {
			return $this->showError('upload.fail');
		}
		->with($data, 'data');
		return $this->showMessage('upload.success');
	}

	public function replaceAction(Request $request) {
		
		if (!$this->loginUser->isExists()) {
			return $this->showError('login.not');
		}
		$aid = $request->get('aid');
		
		Wind::import('SRV:upload.action.PwAttReplaceUpload');
		Wind::import('LIB:upload.PwUpload');
		$bhv = new PwAttReplaceUpload($this->loginUser, $aid);

		$upload = new PwUpload($bhv);
		if (($result = $upload->check()) === true) {
			$result = $upload->execute();
		}
		if ($result !== true) {
			return $this->showError($result->getError());
		}
		->with($bhv->getAttachInfo(), 'data');
		return $this->showMessage('upload.success');
	}

	protected function _getUser() {
		$authkey = 'winduser';
		$pre = Core::C('site', 'cookie.pre');
		$pre && $authkey = $pre . '_' . $authkey;

		$winduser = $request->get($authkey, 'post');

		list($uid, $password) = explode("\t", Tool::decrypt(urldecode($winduser)));
		$user = new PwUserBo($uid);
		if (!$user->isExists() || Tool::getPwdCode($user->info['password']) != $password) {
			return null;
		}
		unset($user->info['password']);
		return $user;
	}
}