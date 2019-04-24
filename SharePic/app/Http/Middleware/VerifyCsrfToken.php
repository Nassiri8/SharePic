<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/test',
        '/user', 
        '/store',
        '/store/add',
        '/login',
        '/store/*',
        '/follow/*',
        '/unfollow/*',
        '/getUserById/*',
        '/getUserById/*',
        '/like/increase/*',
        '/like/decrease/*',
        '/add/comment/*',
        '/getFollower',
        '/getFollowed',
        '/logout'
    ];
}
