<?php

namespace App\Services\credit\ds\relation;

use App\Services\user\ds\relation\user;
use Illuminate\Database\Eloquent\Model;

class creditLogOperate extends Model
{
    protected $table = '_credit_log_operate';
    protected $primaryKey = '';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['uid', 'operate', 'num', 'update_time'];
    protected $guarded = [];

/*    public function user()
    {
        return $this->belongsTo(user::class, 'uid', 'uid');
    }*/
}