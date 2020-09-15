<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class forum extends Model
{
    protected $table = '_bbs_forum';
    protected $primaryKey = 'fid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = [ 'parentid', 'type', 'issub', 'hassub', 'name', 'descrip', 'vieworder', 'across', 'manager', 'uppermanager', 'icon', 'logo', 'fup', 'fupname', 'isshow', 'isshowsub', 'newtime', 'password', 'allow_visit', 'allow_read', 'allow_post', 'allow_reply', 'allow_upload', 'allow_download', 'created_time', 'created_username', 'created_userid', 'created_ip', 'style'];
    protected $guarded = [];

   /* public function forumExtra() {
        return $this->hasOne(forumExtra::class, 'fid', 'fid');
    }

    public function forumStatistics(){
        return $this->hasOne(forumStatistics::class, 'fid', 'fid');
    }

    public function user(){
        return $this->belongsToMany(App\Services\user\ds\relation\user::class, 'forum_user', 'fid', 'uid');
    }

    public function posts(){
        return $this->hasMany(posts::class, 'fid', 'fid');
    }

    public function specialsort(){
        return $this->hasOne(specialsort::class, 'fid', 'fid');
    }

    public function threads(){
        return $this->hasMany(threads::class, 'fid', 'fid');
    }

    public function threadsDigestIndex(){
        return $this->hasMany(threadsDigestIndex::class, 'fid', 'fid');
    }

    public function threadsIndex(){
        return $this->hasMany(threadsIndex::class, 'fid', 'fid');
    }

    public function threadsSort(){
        return $this->hasMany(threadsSort::class, 'fid', 'fid');
    }

    public function topicType(){
        return $this->hasMany(topicType::class, 'fid', 'fid');
    }

    public function topped(){
        return $this->hasMany(topped::class, 'tid', 'fid');
    }*/
}