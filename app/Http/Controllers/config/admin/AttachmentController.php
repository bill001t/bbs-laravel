<?php

Wind::import('ADMIN:library.AdminBaseController');

/**
 * @author Qiong Wu <papa0924@gmail.com> 2011-12-15
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: AttachmentController.php 3284 2011-12-15 08:38:49Z yishuo $
 * @package admin
 * @subpackage controller.config
 */
class AttachmentController extends AdminBaseController {
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$config = Core::C()->getValues('attachment');
		!($post_max_size = ini_get('post_max_size')) && $post_max_size = '2M';
		!($upload_max_filesize = ini_get('upload_max_filesize')) && $upload_max_filesize = '2M';
		$maxSize = min($post_max_size, $upload_max_filesize);

		->with($maxSize, 'maxSize');
		->with($config, 'config');
	}

	/**
	 * 后台设置-附件设置
	 */
	public function dorunAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($pathsize, $attachnum, $extsize) = $request->get(array('pathsize', 'attachnum', 'extsize'), 'post');
		$_extsize = array();
		foreach ($extsize as $key => $value) {
			if (!empty($value['ext'])) $_extsize[$value['ext']] = abs(intval($value['size']));
		}
		$config = new PwConfigSet('attachment');
		$config->set('pathsize', abs(intval($pathsize)))->set('attachnum', abs(intval($attachnum)))->set('extsize', 
			$_extsize)->flush();
		return $this->showMessage('ADMIN:success');
	}

	/**
	 * 附件存储方式设置列表页
	 */
	public function storageAction(Request $request) {
		/* @var $attService PwAttacmentService */
		$attService = app('LIB:storage.PwStorage');
		$storages = $attService->getStorages();
		$config = Core::C()->getValues('attachment');
		$storageType = 'local';
		if (isset($config['storage.type']) && isset($storages[$config['storage.type']])) {
			$storageType = $config['storage.type'];
		}

		$windidStorages = app(AvatarApi::class)->getStorages();
		$windidStorageType = Core::app('windid')->config->attachment->get('storage.type');
		foreach ($windidStorages as $key => $value) {
			if ($value['managelink']) {
				$windidStorages[$key]['managelink'] = str_replace('/', Core::app('windid')->url->base, url($value['managelink']));
			}
		}

		->with($storages, 'storages');
		->with($storageType, 'storageType');
		->with($windidStorages, 'windidStorages');
		->with($windidStorageType, 'windidStorageType');
	}

	/**
	 * 附件存储方式设置列表页
	 */
	public function dostroageAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$att_storage = $request->get('att_storage', 'post');
		$avatar_storage = $request->get('avatar_storage', 'post');

		/* @var $attService PwAttacmentService */
		$attService = app('LIB:storage.PwStorage');
		$_r = $attService->setStoragesComponents($att_storage);
		if ($_r !== true) {
			return $this->showError($_r->getError());
		}
		$config = new PwConfigSet('attachment');
		$config->set('storage.type', $att_storage)->flush();
		
		$result = app(AvatarApi::class)->setStorages($avatar_storage);
		if ($result == '1') {
			Core::C()->setConfig('site', 'avatarUrl', app(AvatarApi::class)->getAvatarUrl());
		}

		return $this->showMessage('ADMIN:success');
	}

	/**
	 * 后台设置-附件缩略设置
	 */
	public function thumbAction(Request $request) {
		$config = Core::C()->getValues('attachment');
		->with($config, 'config');
// 		->with(Core::C('attachment'), 'config');
	}

	/**
	 * 后台设置-附件缩略设置
	 */
	public function dothumbAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($thumb, $thumbsize_width, $thumbsize_height, $quality) = $request->get(
			array('thumb', 'thumbsize_width', 'thumbsize_height', 'quality'), 'post');

		$config = new PwConfigSet('attachment');
		$config->set('thumb', intval($thumb))
			->set('thumb.size.width', $thumbsize_width)
			->set('thumb.size.height', $thumbsize_height)
			->set('thumb.quality', $quality)
			->flush();
		return $this->showMessage('ADMIN:success');
	}

	/**
	 * 后台设置-附件缩略预览
	 */
	public function viewAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($thumb, $thumbsize_width, $thumbsize_height, $quality) = $request->get(
			array('thumb', 'thumbsize_width', 'thumbsize_height', 'quality'), 'post');
		
		Wind::import('LIB:image.PwImage');
		$image = new PwImage(Wind::getRealDir('REP:demo', false) . '/demo.jpg');
		$thumburl = Wind::getRealDir('PUBLIC:attachment', false) . '/demo_thumb.jpg';
		$image->makeThumb($thumburl, $thumbsize_width, $thumbsize_height, $quality, $thumb);
		
		$data = array('img' => Core::url()->attach . '/demo_thumb.jpg?' . time());
		->with($data, 'data');
		return $this->showMessage('ADMIN:success');
	}
}

?>