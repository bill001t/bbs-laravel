<?php
Wind::import('APPS:manage.controller.BaseManageController');
Wind::import('SRV:report.dm.PwReportDm');

/**
 * 前台管理面板 - 举报管理
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: ReportController.php 28816 2013-05-24 09:45:25Z jieyin $
 * @package wind
 */
class ReportController extends BaseManageController {
	private $perpage = 20;
	
	/* (non-PHPdoc)
	 * @see BaseManageController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$result = $this->loginUser->getPermission('panel_report_manage', false, array());
		if (!$result['report_manage']) {
			return $this->showError('REPORT:right.error');
		}
	}
	
	/**
	 * 举报管理
	 *
	 * @return void
	 */
	public function run() {
		list($page, $perpage, $ifcheck, $type) = $request->get(array('page', 'perpage', 'ifcheck', 'type'));
		$page = $page ? $page : 1;
		$perpage = $perpage ? $perpage : $this->perpage;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		
		$count = $this->_getReportDs()->countByType($ifcheck, $type);
		if ($count) {
			$reports = $this->_getReportService()->getReceiverList($ifcheck, $type, $limit, $start);
		}
		$reportTypes = $this->_getReportService()->getTypeName();
		->with($reportTypes, 'reportTypes');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with($reports, 'reports');
		->with(array('ifcheck' => $ifcheck, 'type' => $type), 'args');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:manage.report.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}
		
	/**
	 * 忽略
	 *
	 * @return void
	 */
	public function deleteAction(Request $request) {
		$id = $request->get('id', 'post');
		if (!$id) {
			return $this->showError('operate.fail');
		}
		!is_array($id) && $id = array($id);
		$this->_sendDealNotice($id,'忽略');
		$this->_getReportDs()->batchDeleteReport($id);
		return $this->showMessage('success');
	}
	
	private function _buildNoticeTitle($username,$action) {
		return '您举报的内容已被 <a href="' . url('space/index/run', array('username' => $username)) .'">' . $username . '</a> '.$action.'，感谢您能一起协助我们管理站点。';
	}
	
	/**
	 * 标记处理
	 *
	 * @return void
	 */
	public function dealCheckAction(Request $request) {
		$id = $request->get('id', 'post');
		if (!$id) {
			return $this->showError('operate.fail');
		}
		!is_array($id) && $id = array($id);

		$dm = new PwReportDm();
		$dm->setOperateUserid($this->loginUser->uid)
			->setOperateTime(Tool::getTime())
			->setIfcheck(1);
		$this->_getReportDs()->batchUpdateReport($id,$dm);
		$this->_sendDealNotice($id,'处理');
		return $this->showMessage('success');
	}
	
	private function _sendDealNotice($ids,$action) {
		$reports = $this->_getReportDs()->fetchReport($ids);
		$notice = app('message.srv.PwNoticeService');
		$extendParams = array(
			'operateUserId' => $this->loginUser->uid,
			'operateUsername' => $this->loginUser->username,
			'operateTime' => Tool::getTime(),
			'operateType' => $action,
		); 
		foreach ($reports as $v) {
			$this->_getReportService()->sendNotice($v,$extendParams);
			$content = $this->_buildNoticeTitle($this->loginUser->username,$action);
			$this->_getPwNoticeService()->sendDefaultNotice($v['created_userid'],$content,$content);
		}
		return true;
	}

	/** 
	 * @return PwNoticeService
	 */
	protected function _getPwNoticeService(){
		return app('message.srv.PwNoticeService');
	}
	
	/** 
	 * @return PwReport
	 */
	protected function _getReportDs(){
		return app('report.PwReport');
	}
	
	/** 
	 * @return PwReportService
	 */
	protected function _getReportService(){
		return app('report.srv.PwReportService');
	}
}
?>