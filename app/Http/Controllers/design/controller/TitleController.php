<?php
Wind::import('APPS:design.controller.DesignBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: TitleController.php 28899 2013-05-29 07:23:48Z gao.wanggao $ 
 * @package 
 */
class TitleController extends DesignBaseController{
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		app('design.PwDesignPermissions');
		$permissions = $this->_getPermissionsService()->getPermissionsForModule($this->loginUser->uid,$this->bo->moduleid, $this->pageid);
		if ($permissions < PwDesignPermissions::IS_ADMIN ) return $this->showError("DESIGN:permissions.fail");
	}
	
	public function editAction(Request $request) {
		$titles = $this->bo->getTitle();
		if (!$titles['titles']){
			$titles['titles'] = array(array('title'=>''));
		}
		->with($this->_getDesignService()->getSysFontSize(), 'sysfontsize');
		->with($titles, 'titles');
		->with($this->bo->moduleid, 'moduleid');
	}
	
	public function doeditAction(Request $request) {
		$array = array();
		$html = '';
		$title = $request->get('title','post');
		$link = $request->get('link','post');
		$image = $request->get('image','post');
		$float = $request->get('float','post');
		$margin = $request->get('margin','post');
		//$position = $request->get('position','post');
		//$pixels = $request->get('pixels','post');
		$fontsize = $request->get('fontsize','post');
		$fontcolor = $request->get('fontcolor','post');
		$fontbold = $request->get('fontbold','post');
		$fontunderline = $request->get('fontunderline','post');
		$fontitalic = $request->get('fontitalic','post');
		
		$bgimage = $request->get('bgimage','post');
		$bgcolor = $request->get('bgcolor','post');
		$bgposition = $request->get('bgposition','post');
		
		$styleSrv = $this->_getStyleService();
		
		$background = array();
		$bgimage && $background['image'] = $bgimage;
		$bgcolor && $background['color'] = $bgcolor;
		$bgposition && $background['position'] = $bgposition;
		
		//foreach ($pixels AS &$v) $v = (int)$v ? (int)$v: '';
		foreach ($fontsize AS &$v) $v = (int)$v ? (int)$v: '';
		foreach ($title AS $k=>$value) {
			$_tmp = array(
				'title'=>$title[$k],
				'link'=>$link[$k],
				'image'=>$image[$k],
				'float'=>$float[$k],
				'margin'=>$margin[$k],
				'fontsize'=>$fontsize[$k],
				'fontcolor'=>$fontcolor[$k],
				'fontbold'=>$fontbold[$k],
				'fontunderline'=>$fontunderline[$k],
				'fontitalic'=>$fontitalic[$k],
			);
			$style = $this->_buildTitleStyle($_tmp);
			$styleSrv->setStyle($style);
			list($dom,$jstyle) = $styleSrv->getCss($style);
			$jtitle = $image[$k] ? '<img src="'.$_tmp['image'].'" title="'.Security::escapeHTML($_tmp['title']).'">' : Security::escapeHTML($_tmp['title']);
			if ($jtitle) {
				$html .= '<span ';
				$html .= $jstyle ? 'style="'.$jstyle.'"' : '' ;
				$html .= '>';
				$html .= $_tmp['link'] ? '<a href="'.$_tmp['link'].'">' : '';
				$html .= $jtitle;
				$html .= $_tmp['link'] ? '</a>' : '';
				$html .='</span>';
				$array['titles'][] = $_tmp;
			}
		}
		if ($background) {
			$array['background'] = $background;
			$bg = array('background'=>$background);
			$styleSrv->setStyle($bg);
			list($dom, $data['background']) = $styleSrv->getCss();
		}
		$bgStyle = $data['background'] ? '  style="'.$data['background'].'"' : '';
		if ($html) $html = '<h2 class="cc design_tmode_h2"'.$bgStyle.'>'.$html.'</h2>';
		Wind::import('SRV:design.dm.PwDesignModuleDm');
 		$dm = new PwDesignModuleDm($this->bo->moduleid);
 		$dm->setTitle($array);
		$resource = $this->_getModuleDs()->updateModule($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		->with($html, 'html');
		return $this->showMessage("operate.success");
	}
	
	private function _buildTitleStyle($style) {
		return array(
				'float'=>array('type'=>$style['float'],'margin'=>$style['margin']),
				'font'=>array('size'=>$style['fontsize'],'color'=>$style['fontcolor'],'bold'=>$style['fontbold'],'underline'=>$style['fontunderline'],'italic'=>$style['fontitalic']),
				'background'=>array('color'=>$style['bgcolor'],'image'=>$style['bgimage'],'position'=>$style['bgposition']),
		);
	}
	
	private function _getModuleDs() {
		return app('design.PwDesignModule');
	}
	
	private function _getDesignService() {
		return app('design.srv.PwDesignService');
	}
	
	private function _getStyleService() {
		return app('design.srv.PwDesignStyle');
	}

}
?>