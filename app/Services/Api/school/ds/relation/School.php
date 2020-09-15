<?php

namespace App\Services\Api\school\ds\relation;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = '_school';
    protected $primaryKey = 'schoolid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['schoolid', 'name', 'areaid', 'first_char', 'typeid'];
    protected $guarded = [];

}