<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class posts extends Model
{
    protected $table = '_bbs_posts';
    protected $primaryKey = 'pid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = [];
    protected $guarded = [];

    public function forum(){
        return $this->belongsTo('forum', 'fid', 'fid');
    }

    public function postsTopped(){
        return $this->hasOne('postsTopped', 'pid', 'pid');
    }

    public function specialsort(){
        return $this->hasOne('specialsort', 'pid', 'pid');
    }

    public function threads(){
        return $this->belongsTo('threads', 'tid', 'tid');
    }

    public function threadsBuy(){
        return $this->hasOne('threadsBuy', 'pid', 'pid');
    }

    public function topped(){
        return $this->hasOne('topped', 'pid', 'pid');
    }
}