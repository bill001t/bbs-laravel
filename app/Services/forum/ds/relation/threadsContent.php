<?php

namespace App\Services\forum\ds\relation;

use Illuminate\Database\Eloquent\Model;

class threadsContent extends Model
{
    protected $table = '_bbs_threads_content';
    protected $primaryKey = 'tid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['tid', 'useubb', 'aids', 'content', 'sell_count', 'reminds', 'word_version', 'tags', 'ipfrom', 'manage_remind'];
    protected $guarded = [];

    public function threads(){
        return $this->belongsTo(threads::class, 'tid', 'tid');
    }
}