<?php

namespace App\Services\recycle\ds\relation;

use Illuminate\Database\Eloquent\Model;
use App\Services\forum\ds\relation\PwPost;

class PwReplyRecycle extends Model
{
    protected $table = '_recycle_reply';
    protected $primaryKey = 'pid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['pid', 'tid', 'fid', 'operate_time', 'operate_username', 'reason'];
    protected $guarded = [];

	public function Post(){
        return $this->belongsTo(\PwPost::class, 'pid', 'pid');
    }
}