<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class threads extends Model
{
    protected $table = '_bbs_threads';
    protected $primaryKey = 'tid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['tid', 'fid', 'topic_type', 'subject', 'topped', 'digest','overtime', 'highlight', 'inspect', 'ifshield', 'disabled', 'ischeck', 'replies', 'hits','like_count', 'special', 'tpcstatus', 'ifupload', 'created_time', 'created_username', 'created_userid', 'created_ip', 'modified_time', 'modified_username', 'modified_userid', 'modified_ip', 'lastpost_time', 'lastpost_userid', 'lastpost_username', 'reply_notice', 'reply_topped', 'special_sort', 'app_mark'];
    protected $guarded = [];

    public function forum() {
        return $this->belongsTo(forum::class, 'fid', 'tid');
    }

    public function posts(){
        return $this->hasMany(posts::class, 'tid', 'tid');
    }

    public function postsTopped(){
        return $this->hasMany(postsTopped::class, 'tid', 'tid');
    }

    public function specialsort(){
        return $this->hasMany(specialsort::class, 'tid', 'tid');
    }

    public function threadsBuy(){
        return $this->hasMany(threadsBuy::class, 'tid', 'tid');
    }

    public function threadsCateIndex(){
        return $this->hasOne(threadsCatIndex::class, 'tid', 'tid');
    }

    public function threadsContent(){
        return $this->hasOne(threadsContent::class, 'tid', 'tid');
    }
    public function threadsDigestIndex(){
        return $this->hasOne(threadsDigestIndex::class, 'tid', 'tid');
    }

    public function threadsHits(){
        return $this->hasOne(threadsHits::class, 'tid', 'tid');
    }

    public function threadsIndex(){
        return $this->hasOne(threadsIndex::class, 'tid', 'tid');
    }

    public function threadsOvertime(){
        return $this->hasOne(threadsOvertime::class, 'tid', 'tid');
    }
    public function threadsSort(){
        return $this->hasOne(threadsSort::class, 'tid', 'tid');
    }

    public function topped(){
        return $this->hasMany(topped::class, 'tid', 'tid');
    }
}