<?php

namespace App\Services\forum\ds\traits;

use Illuminate\Foundation\Validation\ValidatesRequests;

Trait threadsSortTrait
{
    use ValidatesRequests;

    protected $rules = [];
    protected $messages = [];
}