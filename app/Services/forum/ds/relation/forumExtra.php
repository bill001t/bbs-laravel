<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class forumExtra extends Model
{
    protected $table = '_bbs_forum_extra';
    protected $primaryKey = 'fid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['fid', 'seo_description', 'seo_keywords', 'settings_basic', 'settings_credit'];
    protected $guarded = [];

    public function forum() {
        return $this->belongsTo(forum::class, 'fid', 'fid');
    }
}