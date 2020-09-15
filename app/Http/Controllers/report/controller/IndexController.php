<?php

/**
 * 举报Controller
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */

class IndexController extends Controller{
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return redirect('u/login/run',array('backurl' => 'my/article/run'));
		}
		if (!$this->loginUser->getPermission('allow_report')) {
			return $this->showError(array('report.allow',array('{grouptitle}' => $this->loginUser->getGroupInfo('name'))));
		}
	}
	
	/**
	 * 举报弹窗
	 *
	 * @return void
	 */
	public function reportAction(Request $request) {
		list($type,$type_id) = $request->get(array('type','type_id'));
		->with($type, 'type');
		->with($type_id, 'type_id');
	}
	
	/**
	 * do举报
	 *
	 * @return void
	 */
	public function doReportAction(Request $request) {
		list($type, $type_id, $reason) = $request->get(array('type', 'type_id', 'reason'), 'post');
		if (!$type_id) {
			return $this->showError('operate.fail');
		}
		$report = app('report.srv.PwReportService');
		$result = $report->sendReport($type,$type_id,$reason);
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('success');
	}
}
