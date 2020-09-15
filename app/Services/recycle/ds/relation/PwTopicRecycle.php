<?php

namespace App\Services\recycle\ds\relation;

use App\Services\forum\ds\relation\PwThread;
use Illuminate\Database\Eloquent\Model;

class PwTopicRecycle extends Model
{
    protected $table = '_recycle_topic';
    protected $primaryKey = 'tid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['tid', 'fid', 'operate_time', 'operate_username', 'reason'];
    protected $guarded = [];

    public function Thread()
    {
        return $this->belongsTo(\PwThread::class, 'pid', 'pid');
    }
}