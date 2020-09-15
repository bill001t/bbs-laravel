<?php
Wind::import('ADMIN:library.AdminBaseController');

/**
 * @author Qiong Wu <papa0924@gmail.com> 2011-12-15
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id$
 * @package admin
 * @subpackage controller.config
 */
class WatermarkController extends AdminBaseController {

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$config = Core::C()->getValues('attachment');
		->with($config, 'config');
		->with($this->getFontList(), 'fontList');
		->with($this->getWaterMarkList(), 'markList');
		
	}

	/**
	 * 后台设置-水印管理
	 */
	public function dorunAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$config = new PwConfigSet('attachment');
		$config->set('mark.limitwidth', abs(intval($request->get('markLimitwidth', 'post'))))
			->set('mark.limitheight', abs(intval($request->get('markLimitheight', 'post'))))
			->set('mark.position', $request->get('markPosition', 'post'))
			->set('mark.gif', $request->get('markGif', 'post'))
			->set('mark.type', $request->get('markType', 'post'))
			->set('mark.text', $request->get('markText', 'post'))
			->set('mark.fontfamily', $request->get('markFontfamily', 'post'))
			->set('mark.fontsize', $request->get('markFontsize', 'post'))
			->set('mark.fontcolor', $request->get('markFontcolor', 'post'))
			->set('mark.quality', abs(intval($request->get('markQuality', 'post'))))
			->set('mark.file', $request->get('markFile', 'post'))
			->set('mark.transparency', abs(intval($request->get('markTransparency', 'post'))))
			->set('mark.quality', abs(intval($request->get('markQuality', 'post'))))
			->flush();
		return $this->showMessage('ADMIN:success');
	}
	
	/**
	 * 水印预览
	 */
	public function viewAction(Request $request) {
		$config = array('mark.limitwidth'=>abs(intval($request->get('markLimitwidth', 'post'))),
			'mark.limitheight'=>abs(intval($request->get('markLimitheight', 'post'))),
			'mark.position'=>$request->get('markPosition', 'post'),
			'mark.gif'=>$request->get('markGif', 'post'),
			'mark.type'=>$request->get('markType', 'post'),
			'mark.text'=>$request->get('markText', 'post'),
			'mark.fontfamily'=>$request->get('markFontfamily', 'post'),
			'mark.fontsize'=>$request->get('markFontsize', 'post'),
			'mark.fontcolor'=>$request->get('markFontcolor', 'post'),
			'mark.quality'=>abs(intval($request->get('markQuality', 'post'))),
			'mark.file'=>$request->get('markFile', 'post'),
			'mark.transparency'=>abs(intval($request->get('markTransparency', 'post'))),
			'mark.quality'=>abs(intval($request->get('markQuality', 'post')))
		);

		Wind::import('LIB:image.PwImage');
		Wind::import('LIB:image.PwImageWatermark');
		
		$image = new PwImage(Wind::getRealDir('REP:demo', false) . '/demo.jpg');
		$watermark = new PwImageWatermark($image);
		$watermark->setPosition($config['mark.position'])
			->setType($config['mark.type'])
			->setTransparency($config['mark.transparency'])
			->setQuality($config['mark.quality'])
			->setDstfile(Wind::getRealDir('PUBLIC:attachment',false) . '/demo.jpg');

		if ($config['mark.type'] == 1) {
			$watermark->setFile($config['mark.file']);
		} else {
			$watermark->setText($config['mark.text'])
				->setFontfamily($config['mark.fontfamily'])
				->setFontsize($config['mark.fontsize'])
				->setFontcolor($config['mark.fontcolor']);
		}
		$watermark->execute();

		->with(Core::url()->attach . '/demo.jpg?' . time(), 'data');
		return $this->showMessage('ADMIN:success');
	}

	/**
	 * 后台设置-水印策略设置
	 */
	public function setAction(Request $request) {
		$config = Core::C()->getValues('attachment');
		->with($config, 'config');
		//扩展：key => title
		$watermarkExt = array('bbs' => '论坛图片上传');
		$watermarkExt = SimpleHook::getInstance('attachment_watermark')->runWithFilters($watermarkExt);
		->with($watermarkExt, 'watermarkExt');
	}
	

	/**
	 * 后台设置-水印策略设置
	 */
	public function dosetAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$ext = $request->get('ext', 'post');
		$extConfig = array();
		foreach ($ext as $key => $value) {
			if ($value == 1) {
				$extConfig[] = $key;
			}
		}
		$config = new PwConfigSet('attachment');
		$config->set('mark.markset', $extConfig)->flush();
		return $this->showMessage('ADMIN:success');
	}

	/**
	 * 获取字体列表
	 *
	 * @return array
	 */
	protected static function getFontList() {
		$_path = Wind::getRealDir('REP:font.');
		return WindFolder::read($_path);
	}

	/**
	 * 获取水印文件列表
	 *
	 * @return array
	 */
	protected static function getWaterMarkList() {
		$_path = Wind::getRealDir('REP:mark.');
		return WindFolder::read($_path);
	}

}
?>