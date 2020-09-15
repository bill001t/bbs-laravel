<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\threadsHits;
use App\Services\forum\ds\traits\threadsHitsTrait;

class PwThreadsHitsDao extends threadsHits
{
	use threadsHitsTrait;

	public function get($tid) {
		return threadsHits::find($tid);
	}

	public function fetch($tids) {
		return threadsHits::whereIn('tid', $tids)
			->get();
	}

	public function add($fields) {
		return threadsHits::create($fields);
	}

	public function _update($tid, $hits) {
		return DB::updae('replace ' . $this->table . ' SET hits=hits+? WHERE tid=?', [$hits, $tid]);
	}

	public function syncHits() {
		DB::updae('UPDATE pw_bbs_threads_hits a LEFT JOIN pw_bbs_threads b ON a.tid=b.tid SET b.hits=b.hits+a.hits', []);
		DB::statement('TRUNCATE TABLE ' . $this->table, []);

		return true;
	}
}