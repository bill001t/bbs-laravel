<?php

namespace App\Services\forum\ds\traits;

use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Core\BaseTrait;

Trait forumTrait
{
    use ValidatesRequests,BaseTrait;

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