<?php

namespace App\Services\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class userEducation extends Model
{
    protected $table = '_user_education';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = [];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(user::class, 'uid', 'uid');
    }
}