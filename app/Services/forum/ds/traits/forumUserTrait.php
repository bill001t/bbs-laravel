<?php

namespace App\Services\forum\ds\traits;

use Illuminate\Foundation\Validation\ValidatesRequests;

Trait forumUserTrait
{
    use ValidatesRequests;

    protected $rules = [];
    protected $messages = [];
}