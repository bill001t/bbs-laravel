<?php

namespace App\Services\tag\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwTagRecord extends Model
{
    protected $table = '_tag_record';
    protected $primaryKey = 'tag_id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['tag_id', 'is_reply', 'update_time'];
    protected $guarded = [];



}