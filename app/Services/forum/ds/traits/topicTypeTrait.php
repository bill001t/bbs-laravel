<?php

namespace App\Services\forum\ds\traits;

use Illuminate\Foundation\Validation\ValidatesRequests;

Trait topicTypeTrait
{
    use ValidatesRequests;

    protected $rules = [];
    protected $messages = [];
}