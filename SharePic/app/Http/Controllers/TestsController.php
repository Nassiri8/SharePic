<?php

    namespace App\Http\Controllers;
    use App\Providers\UserDatabase;
	
Class TestsController extends Controller
{
    function test()
    {
        $db = new UserDatabase();
        $lol = $db->getTest();
        return response()->json($lol);
    }
}