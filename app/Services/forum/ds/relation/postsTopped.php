<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class postsTopped extends Model
{
    protected $table = '_bbs_posts_topped';
    protected $primaryKey = 'pid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = [];
    protected $guarded = [];

    public function posts(){
        return $this->belongsTo(posts::class, 'pid', 'pid');
    }

    public function threads(){
        return $this->belongsTo(threads::class, 'tid', 'tid');
    }
}