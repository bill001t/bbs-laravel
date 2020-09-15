<?php

namespace App\Services\Api\area\ds\relation;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = '_windid_area';//todo:将来要换回
    protected $primaryKey = 'areaid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['areaid', 'name', 'parentid', 'joinname'];
    protected $guarded = [];

}