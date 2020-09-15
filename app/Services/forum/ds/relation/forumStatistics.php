<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class forumStatistics extends Model
{
    protected $table = '_bbs_forum_statistics';
    protected $primaryKey = 'fid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = [];
    protected $guarded = [];

    public function forum() {
        return $this->belongsTo(forum::class, 'fid', 'fid');
    }
}