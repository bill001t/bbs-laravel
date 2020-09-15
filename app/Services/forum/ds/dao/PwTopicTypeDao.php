<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\topicType;
use App\Services\forum\ds\traits\topicTypeTrait;

class PwTopicTypeDao extends topicType
{
	use topicTypeTrait;

	public function addTopicType($fields){
		return topicType::creat($fields);
	}

	public function updateTopicType($id, $fields){
		return topicType::where('id', $id)
			->update($fields);
	}
	
	public function getTopicTypesByFid($fid){
		return topicType::where('fid', $fid)
			->orderby('vieworder', 'asc')
			->get();
	}
	
	public function getTopicType($id){
		return topicType::find($id);
	}
	
	public function fetchTopicType($ids) {
		return topicType::whereIn('id', $ids)
			->get();
	}
	
	public function deleteTopicType($id){
		return topicType::destroy($id);
	}
	
	public function deleteTopictypeByFid($fid){
		return topicType::where('fid', $fid)
			->delete();
	}
	
	public function deleteTopicTypesByParentid($parentid){
		return topicType::where('parentid', $parentid)
			->delete();
	}
}