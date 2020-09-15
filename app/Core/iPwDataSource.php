<?php

namespace App\Core;

interface iPwDataSource
{
    public function getData();
}

interface iPwDataSource2
{
    public function getData($ids);
}