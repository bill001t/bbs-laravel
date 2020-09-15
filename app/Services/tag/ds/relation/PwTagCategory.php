<?php

namespace App\Services\tag\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwTagCategory extends Model
{
    protected $table = '_tag_category';
    protected $primaryKey = 'category_id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['category_id', 'category_name', 'alias', 'vieworder', 'tag_count', 'seo_title', 'seo_description', 'seo_keywords'];
    protected $guarded = [];

    public function tag()
    {
        return $this->belongsToMany(PwTag::class, PwTagCategoryRelation::class, 'category_id', 'tag_id');
    }
}