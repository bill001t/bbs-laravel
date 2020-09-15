<?php

namespace App\Services\hook\ds\relation;

use Illuminate\Database\Eloquent\Model;

class Hook extends Model
{
    protected $table = '_hook';
    protected $primaryKey = 'name';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['name', 'app_name', 'app_id', 'created_time', 'modified_time', 'document'];
    protected $guarded = [];

    public function hookInject(){
        return $this->hasMany(HookInject::class);
    }
}