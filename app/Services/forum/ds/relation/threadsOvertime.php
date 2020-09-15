<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class threadsOvertime extends Model
{
    protected $table = '_bbs_threads_overtime';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = [];
    protected $guarded = [];

    public function threads(){
        return $this->belongsTo(threads::class, 'tid', 'tid');
    }
}