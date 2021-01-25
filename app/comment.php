<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    protected $table = 'comments';
    
        //Relación de muchos a uno
    
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function image(){
        return $this->belongsTo('App\Image', 'image_id');
    }
}
