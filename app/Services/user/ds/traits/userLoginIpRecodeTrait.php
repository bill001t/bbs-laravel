<?php

namespace App\Services\user\ds\traits;

use App\Core\BaseTrait;
use Illuminate\Foundation\Validation\ValidatesRequests;

Trait userLoginIpRecodeTrait
{
    use ValidatesRequests, BaseTrait;

    protected $rules = [];
    protected $messages = [];

    public static function boot()
    {
        parent::boot();

        static::created(function ($topic) {
            SiteStatus::newTopic();
        });
    }
}