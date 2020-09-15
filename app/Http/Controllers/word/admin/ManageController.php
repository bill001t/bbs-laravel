<?php
defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('ADMIN:library.AdminBaseController');

/**
 * 
 * 词语过滤Controller
 *
 * @author Mingqu Luo <luo.mingqu@gmail.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: ManageController.php 28865 2013-05-28 03:34:43Z jieyin $
 * @package wind
 */
class ManageController extends AdminBaseController {
	private $_configName = 'word';
	
	public function run() {
		$total = $this->_getWordDS()->count();
		
		->with($total, 'total');
		->with($total ? $this->_getWordDS()->getWordList() : array(), 'wordList');
		->with($this->_getWordDS()->getTypeMap(), 'typeList');
		->with(Core::C($this->_configName), 'config');
		->with(1, 'page');
		->with(20, 'perpage');
		->with(array(), 'args');
	}
	
	public function addAction(Request $request) {
		->with($this->_getWordDS()->getTypeMap(), 'typeList');
	}
	
	public function doaddAction(Request $request) {
		$word = $request->get('word', 'post');
		$word['word'] = trim($word['word']);
		if (!$word['word']) return $this->showError('WORD:word.empty');
		if (!$word['type']) return $this->showError('WORD:type.empty');
		
		$wordList = explode("\n", $word['word']);
		$wordList = array_unique($wordList);
		
		$wordService = $this->_getWordService();
		$findWord = $wordService->findWord($wordList);
		if ($findWord) {
			$existWord = implode(',', $findWord);
			return $this->showError(array('WORD:show.exist.word', array('{showword}'=>$existWord)));
		}

		if ($this->_getWordDS()->isReplaceWord($word['type']) && !$word['replace']) {
			return $this->showError('WORD:replaceword.empty');
		}

		Wind::import('SRV:word.dm.PwWordDm');
		
		foreach ($wordList as $value) {
			if (!$value) continue;
			
			$dm = new PwWordDm();/* @var $dm PwWordDm */
			$dm->setWord($value)->setWordType($word['type']);
			$this->_getWordDS()->isReplaceWord($word['type']) && $dm->setWordReplace(($word['replace'] ? $word['replace'] : '****'));
			$result = $this->_getWordDS()->add($dm);
			
			if ($result instanceof ErrorBag) {
				return $this->showError($result->getError());
			}
		}
		$this->_getWordFilter()->updateCache();
		return $this->showMessage('success');
	}
	
	public function editAction () {
		$id = intval($request->get('id'));
		if (!$id) return $this->showError('WORD:id_not_exist');
		
		->with($this->_getWordDS()->get($id), 'detail');
		->with($this->_getWordDS()->getTypeMap(), 'typeList');
	}
	
	public function doeditAction () {
		list($id, $word) = $request->get(array('id', 'word'), 'post');
		
		if (!$id) return $this->showError('WORD:id_not_exist');
		
		$word['word'] = trim($word['word']);
		
		if (!$word['word']) return $this->showError('WORD:word.empty');
		if (!$word['type']) return $this->showError('WORD:type.empty');
		
		$wordService = $this->_getWordService();
		
		if ($wordService->isExistWord($word['word'], $id)) {
			return $this->showError('WORD:word.is.exist');
		}
		
		if ($this->_getWordDS()->isReplaceWord($word['type']) && !$word['replace']) {
			return $this->showError('WORD:replaceword.empty');
		}
		
		Wind::import('SRV:word.dm.PwWordDm');
		$dm = new PwWordDm($id);/* @var $dm PwWordDm */
		
		$dm->setWord($word['word'])->setWordType($word['type']);
		$word['replace'] = $this->_getWordDS()->isReplaceWord($word['type']) ? ($word['replace'] ? $word['replace'] : '****') : '';
		$dm->setWordReplace($word['replace']);
	
		if (($result = $this->_getWordDS()->update($dm))instanceof ErrorBag) {
		 	return $this->showError($result->getError());
		};
		$this->_getWordFilter()->updateCache();
		return $this->showMessage('success');
	}
	
	public function deleteAction(Request $request) {
		$id = intval($request->get('id'), 'post');
		if (!$id) return $this->showError('WORD:id_not_exist');
		
		$this->_getWordDS()->delete($id);
		$this->_getWordFilter()->updateCache();
		return $this->showMessage('success');
	}
	
	public function batchdeleteAction(Request $request) {
		list($ids, $checkAll) = $request->get(array('ids', 'checkall'), 'post');
		
		if ($checkAll) {
			list($type, $keyword) = $request->get(array('type', 'keyword'));
			$this->_getWordService()->deleteByCondition($type, $keyword);
			$this->_getWordFilter()->updateCache();
			return $this->showMessage('success');
		}

		if (empty($ids) || !is_array($ids)) return $this->showError('WORD:no_operate_object');
		
		$this->_getWordDS()->batchDelete($ids);
		$this->_getWordFilter()->updateCache();
		return $this->showMessage('success');
	}
	
	public function batcheditAction(Request $request) {
		list($ids, $checkAll) = $request->get(array('ids', 'checkall'));
		if (empty($ids) || !is_array($ids)) return $this->showError('WORD:no_operate_object');
		
		$wordList = $this->_getWordDS()->fetch($ids);
		
		$word = $wordIds = array();
		foreach ($wordList as $key=> $value) {
			$word[] = $value['word'];
			$wordIdList[] = $value['word_id'];
		}
		
		$word = array_unique($word);
		
		->with($word ? implode("\n", $word) : '', 'word');
		->with($wordIdList ? implode(",", $wordIdList) : '', 'wordId');
		->with($this->_getWordDS()->getTypeMap(), 'typeList');
		->with($checkAll, 'checkall');
	}
	
	public function dobatcheditAction(Request $request) {
		list($word, $checkAll) = $request->get(array('word', 'checkall'), 'post');
		
		if ($checkAll) {
			$wordService = $this->_getWordService();
			$word['replace'] = $this->_getWordDS()->isReplaceWord($word['type']) ? ($word['replace'] ? $word['replace'] : '****') : '';
			$this->_getWordDS()->updateAllByTypeAndRelpace($word['type'], $word['replace']);
			return $this->showMessage('success');
		}
		
		$ids = $word['ids'] ? explode(',', $word['ids']) : array();
		$ids = array_unique($ids);
		
		if (empty($ids) || !is_array($ids)) return $this->showError('operate.fail');
		
		$wordService = $this->_getWordService();
		if ($this->_getWordDS()->isReplaceWord($word['type']) && !$word['replace']) {
			return $this->showError('WORD:replaceword.empty');
		}
		
		Wind::import('SRV:word.dm.PwWordDm');
		$dm = new PwWordDm();/* @var $dm PwWordDm */
		
		$dm->setWordType($word['type'] ? $word['type'] : 1);
		$word['replace'] && $dm->setWordReplace($word['replace']);
	
		if (($result = $this->_getWordDS()->batchUpdate($ids, $dm))instanceof ErrorBag) {
		 	return $this->showError($result->getError());
		};
		$this->_getWordFilter()->updateCache();
		return $this->showMessage('success');
	}
	
	public function searchAction(Request $request) {
		list($keyword, $type, $ischeckAll, $page, $perpage) = $request->get(array('keyword', 'type', '_check', 'page', 'perpage'));
		
		$page < 1 && $page = 1;
		
		$perpage = $perpage ? $perpage : 20;
		
		list($offset, $limit) = Tool::page2limit($page, $perpage);
		
		Wind::import('SRV:word.vo.PwWordSo');
		$wordSo = new PwWordSo(); /* @var $wordSo PwWordSo */
		
		$keyword && $wordSo->setWord($keyword);
		$type > 0 && $wordSo->setWordType($type);
		
		$total = $this->_getWordDS()->countSearchWord($wordSo);
		$wordList = $total ? $this->_getWordDS()->searchWord($wordSo, $limit, $offset) : array();
		
		->with($total, 'total');
		->with($wordList, 'wordList');
		->with($this->_getWordDS()->getTypeMap(), 'typeList');
		->with(Core::C($this->_configName), 'config');
		->with($page, 'page');
		->with('search', 'action');
		->with($perpage, 'perpage');
		->with($ischeckAll, 'ischeckAll');
		->with(array(
			'keyword' => $keyword,
			'type' => $type,
			'_check'=> $ischeckAll,
			'perpage'=> $perpage
		), 'args');
		
		return view('manage_run');
	}
	
	public function exportAction(Request $request) {
		$wordService = $this->_getWordService();
		$word = $this->_getWordDS()->fetchAllWord();
		
		$content = ''; 
		foreach ($word as $value) {
			$content .= sprintf('%s|%s', $value['word'], $value['word_type']);
			$content .= $this->_getWordDS()->isReplaceWord($value['word_type']) ? sprintf('|%s', $value['word_replace']) : '';
			$content .= "\r\n";
		}
		
		$filename = sprintf('%s.txt','PwFilterWord');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s',Tool::getTime()+86400).' GMT');
		header('Cache-control: no-cache');
		header('Content-Encoding: none');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Content-type: txt');
		header('Content-Length: '.strlen($content));
		echo $content;
		exit;
	}
	
	public function importAction(Request $request) {
		
	}
	
	public function doimportAction(Request $request) {
		Wind::import('SRV:upload.action.PwWordUpload');
		Wind::import('LIB:upload.PwUpload');
		$bhv = new PwWordUpload();
		$upload = new PwUpload($bhv);

		if (($result = $upload->check()) === true) {
			$result = $upload->execute();
		}

		if ($result !== true) {
			$error = $result->getError();
			if (is_array($error)) {
				list($error, ) = $error;
				if ($error == 'upload.ext.error') {
					return $this->showError('WORD:ext.error');
				}
			}
			
			return $this->showError($result->getError());
		}
		
		$source = $bhv->getAbsoluteFile();

		if (!WindFile::isFile($source)) return $this->showError('operate.fail');
		
		$content = WindFile::read($source);

		pw::deleteAttach($bhv->dir.$bhv->filename, 0, $bhv->isLocal);
		$content = explode("\n", $content);
			
		if (!$content) return $this->showError('WORD:import.data.empty');
		
		$wordService = $this->_getWordService();
		$typeMap = $this->_getWordDS()->getTypeMap();
		
		Wind::import('SRV:word.dm.PwWordDm');
		
		foreach ($content as $value) {
			list($word, $type, $replace) = $this->_parseTextUseInImport($value, $typeMap);
	
			if (!$word || !$type || ($wordService->isExistWord($word))) continue;

			$dm = new PwWordDm();/* @var $dm PwWordDm */
			$dm->setWord($word)->setWordType($type);
			$replace = $this->_getWordDS()->isReplaceWord($type) ? ($replace ? $replace : '****') : '';
			$dm->setWordReplace($replace);

			$this->_getWordDS()->add($dm);
		}
		$this->_getWordFilter()->updateCache();
		return $this->showMessage('success');
	}
		
	public function setconfigAction(Request $request) {
		$config = $request->get('config');
		
		$configService = new PwConfigSet($this->_configName);
		$configService->set('istip', intval($config['tip']))->flush();
		
		return $this->showMessage('success');
	}
	
	public function _parseTextUseInImport($text, $typeMap) {
		list($word, $type, $replace) = explode("|", $text);
		
		$word = trim($word);
		$type = in_array($type, array_keys($typeMap)) ? $type : 1;
		$replace = trim($replace);
		
		return array($word, $type, $replace);
	}
	
	private function _syncHelper() {
		$syncStatus = $this->_getWordSyncService()->status;
		
		->with($syncStatus, 'syncStatus');
		
		if (!$syncStatus) return false;
		
		$this->_getWordSyncService()->setSyncType(($this->_getWordDS()->countByFrom(1) ? 'increase' : 'all'));
		
		->with(array(
						'lasttime'	=>	$this->_getWordSyncService()->lastTimeFromPlatform,
						'syncnum'	=>	$this->_getWordSyncService()->getSyncNum()
		), 'sync');
		
		return true;
	}
	
	/**
	 * get PwWordService
	 * 
	 * @return PwWordService
	 */
	private function _getWordService() {
		return app('word.srv.PwWordService');
	}
	
	/**
	 * get PwWordFilter
	 * 
	 * @return PwWordFilter
	 */
	private function _getWordFilter() {
		return app('word.srv.PwWordFilter');
	}
	
	/**
	 * get PwWord
	 * 
	 * @return PwWord
	 */
	private function _getWordDS() {
		return app('word.PwWord');
	}
}