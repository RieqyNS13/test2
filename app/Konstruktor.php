<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Konstruktor extends Model
{
	protected $guarded = ['id'];
	
    function user(){
    	return $this->belongsTo('App\User','user_id');
    }
    function pengembangan(){
    	return $this->belongsTo('App\Pengembangan','pengembangan_id');
    }
}
