<?php

namespace App\Services\usergroup\ds\traits;

use Illuminate\Foundation\Validation\ValidatesRequests;

Trait userGroupPermissionTrait
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