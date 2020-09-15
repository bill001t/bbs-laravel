<?php

namespace App\Services\config\ds\traits;

use Illuminate\Foundation\Validation\ValidatesRequests;

Trait configTrait
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