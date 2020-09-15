<?php

namespace App\Services\credit\ds\traits;

use Illuminate\Foundation\Validation\ValidatesRequests;

Trait creditLogOperateTrait
{
    use ValidatesRequests;

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