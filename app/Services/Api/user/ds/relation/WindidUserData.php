<?php

namespace App\Services\Api\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class WindidUserData extends Model
{
    protected $table = '_windid_user_data';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['uid', 'messages', 'credit1', 'credit2', 'credit3', 'credit4', 'credit5', 'credit6', 'credit7', 'credit8'];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(WindidUser::class, 'uid', 'uid');
    }
}