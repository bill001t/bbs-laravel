<?php

namespace App\Services\credit\ds\relation;

use Illuminate\Database\Eloquent\Model;

class creditLog extends Model
{
    protected $table = '_credit_log';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $_dataStruct = ['id', 'ctype', 'affect', 'logtype', 'descrip', 'created_userid', 'created_username', 'created_time'];
    protected $guarded = [];

}