<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class threadsSort extends Model
{
    protected $table = '_bbs_threads_sort';
    protected $primaryKey = '';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = [];
    protected $guarded = [];

    public function threads(){
        return $this->belongsTo(threadsIndex::class, 'tid', 'tid');
    }

    public function forum(){
        return $this->belongsTo(forum::class, 'fid', 'fid');
    }
}