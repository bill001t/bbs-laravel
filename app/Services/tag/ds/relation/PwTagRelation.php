<?php

namespace App\Services\tag\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwTagRelation extends Model
{
    protected $table = '_tag_relation';
    protected $primaryKey = 'tag_id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['tag_id', 'content_tag_id', 'type_id', 'param_id', 'ifcheck', 'created_time'];
    protected $guarded = [];

    public function tag()
    {
        return $this->belongsTo(PwTag::class, 'tag_id', 'tag_id');
    }
}