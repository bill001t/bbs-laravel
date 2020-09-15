<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class threadsIndex extends Model
{
    protected $table = '_bbs_threads_index';
    protected $primaryKey = '';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = [];
    protected $guarded = [];

    public function threads(){
        return $this->belongsTo(threads::class, 'tid', 'tid');
    }

    public function forum(){
        return $this->belongsTo(forum::class, 'fid', 'fid');
    }
}