<?php
Wind::import ( 'ADMIN:library.AdminBaseController' );
class ServerController extends AdminBaseController {
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	private $BenchService = null;
	
	/**
	 * 应用中心
	 */
	public function appcenterAction(Request $request) {
		require_once Wind::getRealPath ( 'ACLOUD:aCloud' );
		$_extrasService = ACloudSysCoreCommon::loadSystemClass ( 'extras', 'config.service' );
		ACloudSysCoreCommon::setGlobal ( 'g_ips', explode ( "|", ACloudSysCoreDefine::ACLOUD_APPLY_IPS ) );
		ACloudSysCoreCommon::setGlobal ( 'g_siteurl', ACloudSysCoreDefine::ACLOUD_APPLY_SITEURL ? ACloudSysCoreDefine::ACLOUD_APPLY_SITEURL : $_extrasService->getExtra ( 'ac_apply_siteurl' ) );
		ACloudSysCoreCommon::setGlobal ( 'g_charset', ACloudSysCoreDefine::ACLOUD_APPLY_CHARSET ? ACloudSysCoreDefine::ACLOUD_APPLY_CHARSET : $_extrasService->getExtra ( 'ac_apply_charset' ) );
		$benchService = ACloudSysCoreCommon::loadSystemClass ( 'administor', 'bench.service' );
		$url = $benchService->getLink ( array ('a' => 'forward', 'do' => 'appcenter' ) );
		->with ( $url, 'url' );
	}
	
	public function run() {
		require_once Wind::getRealPath ( 'ACLOUD:aCloud' );
		ACloudSysCoreCommon::setGlobal ( 'g_siteurl', PUBLIC_URL );
		ACloudSysCoreCommon::setGlobal ( 'g_sitename', Core::C('site','info.name') );
		ACloudSysCoreCommon::setGlobal ( 'g_charset', Wind::getApp ()->getResponse ()->getCharset () );
		list ( $this->BenchService, $operate ) = array (ACloudSysCoreCommon::loadSystemClass ( 'administor', 'bench.service' ), strtolower ( $request->get ( "operate" ) ) );
		if ($this->BenchService->isOpen ()) {
			$ac_url = $this->BenchService->getLink ();
			->with ( $ac_url, 'ac_url' );
			return true;
		}
		return $this->apply ();
	}
	
	public function checkAction(Request $request){
		require_once Wind::getRealPath ( 'ACLOUD:aCloud' );
		ACloudSysCoreCommon::setGlobal ( 'g_siteurl', PUBLIC_URL );
		ACloudSysCoreCommon::setGlobal ( 'g_sitename', Core::C('site','info.name') );
		ACloudSysCoreCommon::setGlobal ( 'g_charset', Wind::getApp ()->getResponse ()->getCharset () );
		list ( $this->BenchService, $operate ) = array (ACloudSysCoreCommon::loadSystemClass ( 'administor', 'bench.service' ), strtolower ( $request->get ( "operate" ) ) );
		return ($operate == 'reset') ? $this->reset () : $this->checkEnvironment ();
	}
	
	private function apply() {
		list ( $siteName, $siteUrl, $charset, $version ) = $this->BenchService->getSiteInfo ();
		list ( $bool, $result ) = $this->BenchService->simpleApply ( $siteUrl );
		if (! $bool) {
			->with ( 'error', 'ac_type' );
			->with ( $result, 'ac_message' );
			return false;
		}
		->with ( $siteUrl, 'site_url' );
		->with ( $siteName, 'site_name' );
		->with ( $charset, 'site_charset' );
		->with ( $version, 'site_version' );
		->with ( NEXT_RELEASE, 'site_minor_version' );
		->with ( 'apply', 'ac_type' );
		->with ( $result, 'request_key' );
		->with ( $this->BenchService->getApplySubmitUrl (), 'ac_apply_url' );
	}
	
	private function checkEnvironment() {
		list ( $ac_sitename, $ac_siteurl, $ac_charset, $ac_version ) = $this->BenchService->getSiteInfo ();
		$ac_evninfo = $this->BenchService->getEnvInfo ();
		$ac_applyinfo = $this->BenchService->getLastApplyInfo ();
		->with ( 'check', 'ac_type' );
		->with ( $ac_sitename, 'ac_sitename' );
		->with ( $ac_siteurl, 'ac_siteurl' );
		->with ( $ac_charset, 'ac_charset' );
		->with ( $ac_version, 'ac_version' );
		->with ( $ac_evninfo, 'ac_evninfo' );
		->with ( $ac_applyinfo, 'ac_applyinfo' );
		return view('server_run');
	}
	
	private function reset() {
		$this->BenchService->resetServer ();
		->with ( 'reset', 'ac_type' );
		return view('server_run');
	}
}
?>