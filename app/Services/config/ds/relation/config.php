<?php

namespace App\Services\config\ds\relation;

use Illuminate\Database\Eloquent\Model;

class config extends Model
{
    protected $table = '_common_config';
    protected $primaryKey = '';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = [];
    protected $guarded = [];

}