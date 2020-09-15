<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class forumUser extends Model
{
    protected $table = '_bbs_forum_user';
    protected $primaryKey = 'fid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = [];
    protected $guarded = [];

}