<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy$>
 * @author $Author$ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$ 
 * @package 
 */
class EmotionController extends AdminBaseController {
	
	public function run() {
		$folderList = $this->_getEmotionService()->getFolderList();
		$catList = $this->_getEmotionCategoryDs()->getCategoryList();
		foreach ($catList AS $k => $cat) {
			/*$_apps = explode('|', $cat['emotion_apps']);
			$_appName = '';
			foreach ((array)$_apps AS $_app) {
				$_appName .= $this->_getEmotionService()->getAppcationList($_app) .',';
			}
			$catList[$k]['apps'] = $_apps;
			$catList[$k]['appsname'] = $_appName;*/
			if (Tool::inArray($cat['emotion_folder'], $folderList)) {
				foreach ($folderList AS $key=>$folder) {
					if ($cat['emotion_folder'] == $folder) unset($folderList[$key]);
				}
			}
		}
		
		->with($this->_getEmotionService()->getAppcationList(), 'appList');
		->with($catList, 'catList');
		->with($folderList, 'folderList');
	}
	
	public function dorunAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$isopens = $request->get('isopen','post');
		$catids = $request->get('catid','post');
		is_int($catids) && $catids = array($catids);
		$orderIds = $request->get('category_orderid','post');
		$catnames = $request->get('category_name','post');
		//$apps = $request->get('apps','post');
		if (!$catids)  return $this->showError('ADMIN:fail');
		Wind::import('SRV:emotion.dm.PwEmotionCategoryDm');
		foreach ($catids AS $k=>$v) {
			if (!$catnames[$v]) return $this->showError('ADMIN:catname.empty');
			$dm = new PwEmotionCategoryDm($v);
			$dm->setCategoryMame($catnames[$v])
				->setEmotionApps(array('bbs'))
				->setOrderId($orderIds[$v])
				->setIsopen($isopens[$v]);
			$this->_getEmotionCategoryDs()->updateCategory($dm);
		}
		return $this->showMessage("MEDAL:success");
	}
	
	public function doaddAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		Wind::import('SRV:emotion.dm.PwEmotionCategoryDm');
 		$dm = new PwEmotionCategoryDm();
 		$dm->setCategoryMame($request->get('catname','post'))
 			->setEmotionFolder($request->get('folder','post'))
 			->setEmotionApps(array('bbs'))
 			->setOrderId((int)$request->get('orderid','post'))
 			->setIsopen(1);
 		$resource = $this->_getEmotionCategoryDs()->addCategory($dm);
 		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		return $this->showMessage("MEDAL:success");
	}

	public function deletecateAction(Request $request) {
		$cateId = (int)$request->get('cateid', 'post');
		if (!$cateId) {
			return $this->showError('operate.fail');
		}

		$this->_getEmotionCategoryDs()->deleteCategory($cateId);
		$this->_getEmotionDs()->deleteEmotionByCatid($cateId);

		return $this->showMessage('success', 'emotion/emotion/run/', true);
	}
	
	public function emotionAction(Request $request) {
		$catId = (int)$request->get('catid','get');
		$category = $this->_getEmotionCategoryDs()->getCategory($catId);
		if (!$folder = $category['emotion_folder'])  return $this->showError('ADMIN:fail');
		$emotionList = $this->_getEmotionDs()->getListByCatid($catId);
		$folderEmotion = $this->_getEmotionService()->getFolderIconList($folder);
		foreach ($emotionList AS $key=>$emotion) {
			$emotionList[$key]['sign'] = '[s:' . ($emotion['emotion_name'] ? $emotion['emotion_name'] : $emotion['emotion_id']) . ']';
			foreach ($folderEmotion AS $k=>$val) {
				if ($emotion['emotion_icon'] == $val) unset($folderEmotion[$k]);
			}
		}
		$url = Core::getGlobal('url', 'res').'/images/emotion/';
		->with($emotionList, 'emotionList');
		->with($folderEmotion, 'folderEmotion');
		->with($folder, 'folder');
		->with($catId, 'catid');
		->with($url, 'iconUrl');
		
	}
	
	public function dobatchaddAction(Request $request) {
		$emotionIds = $request->get('emotionid','post');
		is_int($emotionIds) && $emotionIds = array($emotionIds);
		$emotionNames = $request->get('emotionname','post');
		$icons = $request->get('icon','post');
		$orderIds = $request->get('orderid','post');
		$catId = (int)$request->get('catid','post');
		$category = $this->_getEmotionCategoryDs()->getCategory($catId);
		if (!$folder = $category['emotion_folder']) return $this->showError('ADMIN:fail');
		Wind::import('SRV:emotion.dm.PwEmotionDm');
		foreach ($emotionIds AS $v => $vv) {
			if (!$icons[$v]) continue;
			$dm = new PwEmotionDm();
			$dm->setCategoryId($catId)
				->setEmotionFolder($folder)
				->setEmotionName($emotionNames[$v])
				->setEmotionIcon($icons[$v])
				->setVieworder($orderIds[$v]);
			$this->_getEmotionDs()->addEmotion($dm);
		}
		return $this->showMessage("MEDAL:success");
	}
	
	public function dobatcheditAction(Request $request) {
		$emotionIds = $request->get('emotionid','post');
		is_int($emotionIds) && $emotionIds = array($emotionIds);
		$emotionNames = $request->get('emotionname','post');
		$orderIds = $request->get('orderid','post');
		$isuseds = $request->get('isused','post');
		Wind::import('SRV:emotion.dm.PwEmotionDm');
		foreach ($emotionIds AS $k=>$v) {
			$dm = new PwEmotionDm($emotionIds[$k]);
			$dm->setEmotionName($emotionNames[$k])
				->setVieworder($orderIds[$k])
				->setIsused($isuseds[$k]);
			$this->_getEmotionDs()->updateEmotion($dm);
		}
		return $this->showMessage("MEDAL:success");
	}
	
	public function dousedAction(Request $request) {
		$emotionId = (int)$request->get('emotionid', 'post');
		$used = (int)$request->get('used','post');
		if ($emotionId < 1) return $this->showError('ADMIN:fail');
		$used = $used > 0 ? 1 : 0;
		Wind::import('SRV:emotion.dm.PwEmotionDm');
		$dm = new PwEmotionDm($emotionId);
		$dm->setIsused($used);
		$resource = $this->_getEmotionDs()->updateEmotion($dm);
 		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		return $this->showMessage("MEDAL:success");
	}
	
	private function _getEmotionService() {
		return app('emotion.srv.PwEmotionService');
	}

	private function _getEmotionDs() {
		return app('emotion.PwEmotion');
	}
	
	private function _getEmotionCategoryDs() {
		return app('emotion.PwEmotionCategory');
	}
}
?>