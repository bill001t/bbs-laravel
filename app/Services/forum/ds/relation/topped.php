<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class topped extends Model
{
    protected $table = '_bbs_topped';
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

    public function posts(){
        return $this->belongsTo(posts::class, 'pid', 'pid');
    }

}