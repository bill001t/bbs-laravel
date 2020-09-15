<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
/*    public function test(){
        $this->b();
        return view('test')
            ->with('a', 111111);
    }
    function b(){
        view()->composer('testin', function($view){
            $view->with('b', 2);
        });
    }*/

    public function test(){
        return self::$router->currentRouteName();
    }
}