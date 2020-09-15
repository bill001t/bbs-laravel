<?php

namespace App\Services\announce\ds\relation;

use Illuminate\Database\Eloquent\Model;

class Announce extends Model
{
    protected $table = '_announce';
    protected $primaryKey = 'aid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['aid', 'vieworder', 'created_userid', 'typeid', 'url', 'subject', 'content', 'start_date', 'end_date'];
    protected $guarded = [];


}