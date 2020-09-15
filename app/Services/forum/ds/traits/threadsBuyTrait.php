<?php

namespace App\Services\forum\ds\traits;

use Illuminate\Foundation\Validation\ValidatesRequests;

Trait threadsBuyTrait
{
    use ValidatesRequests;

    protected $rules = [];
    protected $messages = [];
}