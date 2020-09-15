<?php
Wind::import('LIB:base.PwBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy$>
 * @author $Author$ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$ 
 * @package 
 */
class IndexController extends Controller{
	
	public function run() {
		$array = array();
		$url = Core::getGlobal('url', 'res').'/images/emotion/';
		$type = $request->get('type','get');
		if (empty($type)) $type = 'bbs';
		$catList = $this->_getCategoryDs()->getCategoryList($type, 1);
		!is_array($catList) && $catList = array();
		$categoryIds = array_keys($catList);
		$list = $this->_getEmotionDs()->fetchEmotionByCatid($categoryIds);
		foreach ($list AS $emotion) {
			$_emotion['sign'] = '[s:' . ($emotion['emotion_name'] ? $emotion['emotion_name'] : $emotion['emotion_id']) . ']';
			$_emotion['url'] = $url . $emotion['emotion_folder'] . '/' . $emotion['emotion_icon'];
			$_emotion['name'] = $emotion['emotion_name'];
			$array[$emotion['category_id']]['category'] = $catList[$emotion['category_id']]['category_name'];
			$array[$emotion['category_id']]['emotion'][] = $_emotion;
		}
		foreach ($catList AS $k => $v) {
			if (!$array[$k]) continue;
			$_array[] = $array[$k];
		}
		->with($_array, 'data');
		return $this->showMessage('success');
	}

	private function _getEmotionDs() {
		return app('emotion.PwEmotion');
	}
	
	private function _getCategoryDs() {
		return app('emotion.PwEmotionCategory');
	}
}
?>