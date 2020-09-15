<?php

namespace App\Services\attach\ds\relation;

use Illuminate\Database\Eloquent\Model;

class ThreadAttachBuy extends Model
{
    protected $table = '_attachs_thread_buy';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['id', 'aid', 'created_userid', 'created_time', 'ctype', 'cost'];
    protected $guarded = [];


}