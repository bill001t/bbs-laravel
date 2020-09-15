<?php

namespace App\Services\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class userBlack extends Model
{
    protected $table = '_windid_user_black';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['uid', 'blacklist'];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(user::class, 'uid', 'uid');
    }
}