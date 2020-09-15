<?php

namespace App\Services\tag\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwTag extends Model
{
    protected $table = '_tag';
    protected $primaryKey = 'tag_id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['tag_id', 'parent_tag_id', 'ifhot', 'tag_name', 'tag_logo', 'iflogo', 'excerpt', 'content_count', 'attention_count', 'created_userid', 'seo_title', 'seo_description', 'seo_keywords'];
    protected $guarded = [];

    public function tagCategory(){
        return $this->belongsToMany(PwTagCategory::class, PwTagCategoryRelation::class, 'tag_id', 'category_id');
    }

    public function tagAttention(){
        return $this->hasMany(PwTagAttention::class, 'tag_id', 'tag_id');
    }

    public function tagRelation(){
        return $this->hasMany(PwTagRelation::class, 'tag_id', 'tag_id');
    }
}