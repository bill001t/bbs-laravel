<?php

namespace App\Services\site\ds\relation;

use Illuminate\Database\Eloquent\Model;

class bbsInfo extends Model
{
    protected $table = '_bbsinfo';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['id', 'newmember', 'totalmember', 'higholnum', 'higholtime', 'yposts', 'hposts'];
    protected $guarded = [];

}