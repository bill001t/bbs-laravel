<?php

namespace App\Services\tag\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwTagAttention extends Model
{
    protected $table = '_tag_attention';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['uid', 'tag_id', 'last_read_time'];
    protected $guarded = [];

    public function tag()
    {
        return $this->belongsTo(PwTag::class, 'tag_id', 'tag_id');
    }
}