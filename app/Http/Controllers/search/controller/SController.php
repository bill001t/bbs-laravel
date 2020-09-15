<?php

class SController extends Controller{
	
	public function run() {
		$keywords = $request->get('keyword');
		if (!Core::C('site', 'search.isopen')) {
			return redirect('search/search/run', array('keyword' => $keywords)));
		}
		return redirect('app/index/run?app=search',array('keywords' => $keywords));
	}

}