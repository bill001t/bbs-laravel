<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class threadsBuy extends Model
{
    protected $table = '_bbs_threads_buy';
    protected $primaryKey = 'tid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = [];
    protected $guarded = [];

    public function threads(){
        return $this->belongsTo(threads::class, 'tid', 'tid');
    }

    public function posts(){
        return $this->belongsTo(posts::class, 'pid', 'pid');
    }

    public function user(){
        return $this->belongsTo(App\Services\user\ds\relation\user::class, 'uid', 'created_userid');
    }
}