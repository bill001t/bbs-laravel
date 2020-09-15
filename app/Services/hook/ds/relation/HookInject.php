<?php

namespace App\Services\hook\ds\relation;

use Illuminate\Database\Eloquent\Model;

class HookInject extends Model
{
    protected $table = '_hook_inject';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['id', 'app_id', 'app_name', 'hook_name', 'alias', 'class', 'method', 'loadway', 'expression', 'created_time', 'modified_time', 'description'];
    protected $guarded = [];

    public function hook()
    {
        return $this->belongsTo(Hook::class);
    }
}