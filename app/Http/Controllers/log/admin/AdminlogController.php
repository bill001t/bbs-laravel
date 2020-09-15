<?php
Wind::import('ADMIN:library.AdminBaseController');

/**
 * Enter description here .
 * ..
 * 
 * @author Zerol Lin <zerol.lin@me.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 */
class AdminlogController extends AdminBaseController {

	public function run() {
		$keyword = $request->get('keyword');
		$page = (int) $request->get('page');
		
		/* @var $menuService AdminMenuService */
		$menuService = app('ADMIN:service.srv.AdminMenuService');
		$authStruts = $menuService->getMenuAuthStruts();
		$menus = $menuService->getMenuTable();
		
		/* @var $logService AdminLogService */
		$logService = app('ADMIN:service.srv.AdminLogService');
		$logs = $logService->readLog();
		
		$count = $logs[0];
		$perpage = 10;
		$page < 1 && $page = 1;
		if ($count % $perpage == 0)
			$numofpage = floor($count / $perpage);
		else
			$numofpage = floor($count / $perpage) + 1;
		if ($page > $numofpage) $page = $numofpage;
		$pagemin = min(($page - 1) * $perpage, $count);
		$pagemax = min($pagemin + $perpage - 1, $count);
		
		$num = 0;
		for ($i = $logs[0]; $i > 0; $i--) {
			if (empty($keyword) || false !== strpos($logs[$i], $keyword)) {
				if ($num >= $pagemin && $num <= $pagemax) {
					$detail = explode("|", $logs[$i]);
					if (false !== strpos($detail[2], '/')) {
						list($m, $c, $a) = explode('/', $detail[2]);
						$authKeys = array();
						if (isset($authStruts[$m][$c][$a])) $authKeys += $authStruts[$m][$c][$a];
						if (isset($authStruts[$m][$c]['_all'])) $authKeys += $authStruts[$m][$c]['_all'];
						!empty($authKeys) && $detail[2] = $menus[$authKeys[0]]['name'];
					}
					$result[] = $detail;
				}
				$num++;
			}
		}
		
		->with($this->isFounder($this->loginUser->username), 'isFound');
		->with($keyword, 'keyword');
		->with($result, 'logs');
		->with(array('keyword' => $keyword), 'searchData');
		->with($page, 'page');
		->with($num, 'count');
		->with($perpage, 'perpage');
		
		return view('manage_admin');
	}

	public function clearAction(Request $request) {
		if (!$this->isFounder($this->loginUser->username)) return $this->showError('fail');
		
		/* @var $logService AdminLogService */
		$logService = app('ADMIN:service.srv.AdminLogService');
		$logService->clear();
		return $this->showMessage('success');
	}
}