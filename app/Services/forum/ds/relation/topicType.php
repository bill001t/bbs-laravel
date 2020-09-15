<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class topicType extends Model
{
    protected $table = '_bbs_topic_type';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = [];
    protected $guarded = [];

    public function forum(){
        return $this->belongsTo(forum::class, 'fid', 'fid');
    }
}