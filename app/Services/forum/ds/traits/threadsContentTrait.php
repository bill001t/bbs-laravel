<?php

namespace App\Services\forum\ds\traits;

use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Core\BaseTrait;

Trait threadsContentTrait
{
    use ValidatesRequests,BaseTrait;

    protected $rules = [];
    protected $messages = [];
}