<?php

namespace App\Services\Api\config\ds\relation;

use Illuminate\Database\Eloquent\Model;

class WindidConfig extends Model
{
    protected $table = '_windid_config';
    protected $primaryKey = 'name';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['name', 'namespace', 'value', 'vtype', 'descrip'];
    protected $guarded = [];
}