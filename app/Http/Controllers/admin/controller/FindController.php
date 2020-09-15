<?php
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('ADMIN:service.srv.AdminSearchService');
/**
 * 后台搜索
 *
 * @author peihong.zhangph <zhangpeihong@aliyun.com> 2011-11-12
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: SearchController.php 3219 2012-08-16 06:43:45Z peihong.zhangph $
 * @package admin
 * @subpackage controller
 */
class FindController extends AdminBaseController {
	public function run() {
		$keyword = $request->get('keyword');
		/* @var $menuService AdminMenuService */
		$menuService = app('ADMIN:service.srv.AdminMenuService');
		$menus = $menuService->getMyMenus($this->loginUser);
		$searcher = new AdminSearchService($keyword, $menus);
		$result = $searcher->search();
		->with($keyword, 'keyword');
		->with($result, 'result');
	}
}