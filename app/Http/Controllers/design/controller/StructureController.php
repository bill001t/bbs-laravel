<?php
Wind::import('LIB:base.PwBaseController');
Wind::import('SRV:design.bo.PwDesignStructureBo');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: StructureController.php 28899 2013-05-29 07:23:48Z gao.wanggao $ 
 * @package 
 */
class StructureController extends PwBaseController{
	
	public $bo;
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		Wind::import('SRV:design.PwDesignPermissions');
		$permissions = $this->_getPermissionsService()->getPermissionsForUserGroup($this->loginUser->uid);
		if ($permissions < PwDesignPermissions::IS_DESIGN ) return $this->showError("DESIGN:permissions.fail");
		$name = $request->get('name','post');
		Wind::import('SRV:design.bo.PwDesignStructureBo');
		$this->bo = new PwDesignStructureBo($name);
	}
	
	public function titleAction(Request $request) {
		$titles = $this->bo->getTitle();
		$pageid = (int)$request->get('pageid', 'post');
		$title = $request->get('title', 'post');
		$tab = $request->get('tab', 'post');
		if (!$titles['titles']){
			if ($tab) {
				$i = 1;
				foreach ($tab AS $v) {
					$titles['titles'][] = array('title'=>'栏目'.$i,'tab'=>$v);
					$i++;
				}
				->with('tab', 'structure');
			} else {
				$titles['titles'] = array(array('title'=>$title));
			}
		}
		->with($this->_getDesignService()->getSysFontSize(), 'sysfontsize');
		->with($titles, 'titles');
		->with($this->bo->name, 'name');
		->with($pageid, 'pageid');
	}
	
	/**
	 * 拖拉模块标题修改
	 * Enter description here ...
	 */
	public function doedittitleAction(Request $request) {
		$html = '';
		$array = array();
		$pageid = (int)$request->get('pageid', 'post');
		$title = $request->get('title','post');
		if ($pageid < 1) return $this->showError("permissions.fail");
		$link = $request->get('link','post');
		$image = $request->get('image','post');
		$float = $request->get('float','post');
		$margin = $request->get('margin','post');
		$fontsize = $request->get('fontsize','post');
		$fontcolor = $request->get('fontcolor','post');
		$fontbold = $request->get('fontbold','post');
		$fontunderline = $request->get('fontunderline','post');
		$fontitalic = $request->get('fontitalic','post');
		$bgimage = $request->get('bgimage','post');
		$bgcolor = $request->get('bgcolor','post');
		$bgposition = $request->get('bgposition','post');
		$structure = $request->get('structure','post');
		$tab = $request->get('tab','post');
		$styleSrv = $this->_getStyleService();
		$_n = 0;
		foreach ($tab AS $v) {
			if ($v){
				list($t,$n) = explode('_', $v);
				if ($n >= $_n) $_n = $n + 1;
			}
		}
		$background['image'] = $bgimage;
		$background['color'] = $bgcolor;
		$background['position'] = $bgposition;
		foreach ($title AS $k=>$value) {
			$_tmp = array(
				'title'=>Security::escapeHTML($title[$k]),
				'link'=>$link[$k],
				'image'=>$image[$k],
				'float'=>$float[$k],
				'margin'=>(int)$margin[$k],
				'fontsize'=>(int)$fontsize[$k],
				'fontcolor'=>$fontcolor[$k],
				'fontbold'=>$fontbold[$k],
				'fontunderline'=>$fontunderline[$k],
				'fontitalic'=>$fontitalic[$k],
			);
			$style = $this->_buildTitleStyle($_tmp);
			$styleSrv->setStyle($style);
			list($dom,$jstyle) = $styleSrv->getCss();
			$jtitle = $image[$k] ? '<img src="'.$_tmp['image'].'" title="'.$_tmp['title'].'">' : $_tmp['title'];
			if ($jtitle) {
				if ($structure == 'tab') {
					if (!$tab[$k]) {
						$tab[$k] = 'tab_' . $_n;
						$_n++;
					}

					$html .= '<li role="tab">';
					$html .= '<a data-id="'.$tab[$k].'" href="'.$_tmp['link'].'"';
					$html .= $jstyle ? ' style="'.$jstyle.'"' : '' ;
					$html .= '>';
					$html .= $jtitle;
					$html .= '</a>';
					$html .= '</li>' ;
					$_tmp['tab'] = $tab[$k];
				} else {
					$html .= '<span';
					$html .= $jstyle && !$_tmp['link']? ' style="'.$jstyle.'"' : '' ;
					$html .= '>';
					$html .= $_tmp['link'] ? '<a href="'.$_tmp['link'].'" style="'.$jstyle.'">' : '';
					$html .= $jtitle;
					$html .= $_tmp['link'] ? '</a>' : '';
					$html .= '</span>';
				}
				$array['titles'][] = $_tmp;
			}
		}
		
		$data['tab'] = $html;
		$data['tabName'] = $tab;
		if ($background) {
			$array['background'] = $background;
			$bg = array('background'=>$background);
			$styleSrv->setStyle($bg);
			list($dom, $data['background']) = $styleSrv->getCss();
		}
		Wind::import('SRV:design.dm.PwDesignStructureDm');
 		$dm = new PwDesignStructureDm();
 		$style = $this->bo->getStyle();
 		$dm->setStructTitle($array)
 			->setStructname($this->bo->name)
 			->setStructStyle($style['font'], $style['link'], $style['border'], $style['margin'], $style['padding'], $style['background'], $style['styleclass']);
		$resource = $this->_getStructureDs()->replaceStruct($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		->with($data, 'html');
		return $this->showMessage("operate.success");
	}
	
	//导入模块的标题编辑
	public function editAction(Request $request) {
		$pageid = (int)$request->get('pageid', 'post');
		$title = $this->bo->getTitle();
		->with($title, 'title');
		->with($this->bo->name, 'name');
		->with($pageid, 'pageid');
	}
	
	public function doeditAction(Request $request) {
		$pageid = (int)$request->get('pageid', 'post');
		$title = $request->get('title','post');
		$struct = $this->bo->getStructure();
		if (!$struct) return $this->showMessage("operate.fail");
		Wind::import('SRV:design.dm.PwDesignStructureDm');
 		$dm = new PwDesignStructureDm();
 		$dm->setStructTitle($title)
 			->setStructname($this->bo->name);
		$resource = $this->_getStructureDs()->replaceStruct($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		
		Wind::import('SRV:design.bo.PwDesignPageBo');
		$pageBo = new PwDesignPageBo($pageid);
		$pageInfo = $pageBo->getPage();
		
		Wind::import('SRV:design.srv.PwPortalCompile');
		$compile = new PwPortalCompile($pageBo);
		if ($pageInfo['page_type'] == PwDesignPage::PORTAL) {
			$compile->replaceTitle($this->bo->name, $title);
		} elseif ($pageInfo['page_type'] == PwDesignPage::SYSTEM) {
			!$struct['segment'] && $struct['segment'] = '';
			$compile->replaceTitle($this->bo->name, $title, $struct['segment']);
		}
		
		->with($title, 'html');
		return $this->showMessage("operate.success");
	}
	
	public function styleAction(Request $request) {
		$srv = $this->_getDesignService();
		->with($srv->getSysStyleClass(), 'sysstyle');
		->with($srv->getSysFontSize(), 'sysfontsize');
		->with($srv->getSysBorderStyle(), 'sysborder');
		->with($srv->getSysLineWidth(), 'syslinewidth');
		->with($this->bo->getStyle(), 'style');
		->with($this->bo->name, 'name');
	}
	
	public function doeditstyleAction(Request $request) {
		$styleclass = $request->get('styleclass','post');
		$font = $request->get('font','post');
		$link = $request->get('link','post');
		$border = $request->get('border','post');
		$margin = $request->get('margin','post');
		$padding = $request->get('padding','post');
		$background = $request->get('background','post');
		
		if ($border['isdiffer']) {
			unset($border['linewidth']);
			unset($border['style']);
			unset($border['color']);
		} else {
			unset($border['top']);
			unset($border['left']);
			unset($border['right']);
			unset($border['bottom']);
		}
		
		if ($margin['isdiffer']) {
			unset($margin['both']);
		} else {
			unset($margin['top']);
			unset($margin['right']);
			unset($margin['left']);
			unset($margin['bottom']);
		}
		if ($padding['isdiffer']) {
			unset($padding['both']);
		} else {
			unset($padding['top']);
			unset($padding['right']);
			unset($padding['left']);
			unset($padding['bottom']);
		}
		
		Wind::import('SRV:design.dm.PwDesignStructureDm');
 		$dm = new PwDesignStructureDm();
 		$dm->setStructStyle($font,$link,$border,$margin,$padding,$background,$styleclass)
 			->setStructName($this->bo->name)
 			->setStructTitle($this->bo->getTitle());
		$resource = $this->_getStructureDs()->replaceStruct($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());

		
		$style = $dm->getStyle();
		//$style = array('font'=>$font, 'link'=>$link, 'border'=>$border, 'margin'=>$margin, 'padding'=>$padding, 'background'=>$background, 'styleclass'=>$styleclass);
		$styleSrv = $this->_getStyleService();
		$styleSrv->setDom($this->bo->name);
		
		$styleSrv->setStyle($style);//$this->differStyle($style)
		$_style['styleDomId'] = $styleSrv->getCss($style);
		$_style['styleDomIdLink'] = $styleSrv->getLink($style);
		$_style['styleDomClass'] = $styleclass;
		->with($_style, 'html');
		return $this->showMessage("operate.success");
	}
	
	public function deleteAction(Request $request) {
		$this->_getStructureDs()->deleteStruct($this->bo->name);
		return $this->showMessage("operate.success");
	}
	
	private function _buildTitleStyle($style) {
		return array(
				'float'=>array('type'=>$style['float'],'margin'=>$style['margin']),
				'font'=>array('size'=>$style['fontsize'],'color'=>$style['fontcolor'],'bold'=>$style['fontbold'],'underline'=>$style['fontunderline'],'italic'=>$style['fontitalic']),
				//'background'=>array('color'=>$style['bgcolor'],'image'=>$style['bgimage'],'position'=>$style['bgposition']),
		);
	}
	
	private function differStyle($style) {
		$array = array('top', 'right', 'bottom', 'left');
		$border = $style['border'];
		$border['isdiffer'] = 1;
		if ($border['linewidth']) {
			$border['top']['linewidth'] = (int)$border['linewidth'];
			$border['right']['linewidth'] = (int)$border['linewidth'];
			$border['bottom']['linewidth'] = (int)$border['linewidth'];
			$border['left']['linewidth'] = (int)$border['linewidth'];
			unset($border['linewidth']);
		}
		
		foreach ($array AS $v) {
			$border[$v]['linewidth']= (int)$border[$v]['linewidth'];
		}
		
		if ($border['style']) {
			$border['top']['style'] = $border['style'];
			$border['right']['style'] = $border['style'];
			$border['bottom']['style'] = $border['style'];
			$border['left']['style'] = $border['style'];
			unset($border['style']);
		}
		
		foreach ($array AS $v) {
			$border[$v]['style']= isset($border[$v]['style']) ? $border[$v]['style'] : 'none';
		}
		
		if ($border['color']) {
			$border['top']['color'] = $border['color'];
			$border['right']['color'] = $border['color'];
			$border['bottom']['color'] = $border['color'];
			$border['left']['color'] = $border['color'];
			unset($border['color']);
		}
		foreach ($array AS $v) {
			$border[$v]['color']= isset($border[$v]['color']) ? $border[$v]['color'] : '';
		}
		$style['border'] = $border;
		return $style;
	}
	
	protected function _getPermissionsService() {
		return app('design.srv.PwDesignPermissionsService');
	}
	
	private function _getStructureDs() {
		return app('design.PwDesignStructure');
	}
	
	private function _getDesignService() {
		return app('design.srv.PwDesignService');
	}
	
	private function _getStyleService() {
		return app('design.srv.PwDesignStyle');
	}
	
	private function _getBakDs() {
		return app('design.PwDesignBak');
	}
}
?>