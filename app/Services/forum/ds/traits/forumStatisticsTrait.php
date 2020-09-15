<?php

namespace App\Services\forum\ds\traits;

use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Core\BaseTrait;

Trait forumStatisticsTrait
{
    use ValidatesRequests,BaseTrait;

    protected $rules = [];
    protected $messages = [];
}