<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

Class Image{

    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image', 'description', 'like' , 'localisation' 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'remember_token'
    ];

    protected $attributes =[
        'like'=>0
    ];
    
    /*function getImageByDate()
    {
        $img = DB::table('image')->get();
        return $img;
    }*/
}