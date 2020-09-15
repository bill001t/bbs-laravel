<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class specialsort extends Model
{
    protected $table = '_bbs_specialsort';
    protected $primaryKey = 'fid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['fid', 'tid', 'extra', 'sort_type', 'created_time', 'end_time'];
    protected $guarded = [];

    function forum(){
        return $this->belongsTo(forum::class, 'fid', 'fid');
    }

    function posts(){
        return $this->belongsTo(posts::class, 'fid', 'fid');
    }

    function threads(){
        return $this->belongsTo(threads::class, 'fid', 'fid');
    }
}