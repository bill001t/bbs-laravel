<?php

namespace App\Services\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class userData extends Model
{
    protected $table = '_user_data';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['uid', 'lastvisit', 'lastlogintip', 'lastpost', 'lastactivetime', 'onlinetime', 'trypwd', 'findpwd', 'postcheck', 'message_tone', 'messages', 'notices', 'postnum', 'digest', 'todaypost', 'todayupload', 'follows', 'fans', 'likes', 'punch', 'join_forum', 'recommend_friend', 'last_credit_affect_log', 'medal_ids', 'credit1', 'credit2', 'credit3', 'credit4', 'credit5', 'credit6', 'credit7', 'credit8'];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(user::class, 'uid', 'uid');
    }

}