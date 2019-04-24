<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

class UserDatabase
{
    function getTest()
    {
        $test = DB::table('users')->get();
        return $test;
    }
}
