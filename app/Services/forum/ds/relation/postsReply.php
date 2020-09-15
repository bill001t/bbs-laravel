<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class postsReply extends Model
{
    protected $table = '_bbs_posts_reply';
    protected $primaryKey = 'pid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = [];
    protected $guarded = [];
}