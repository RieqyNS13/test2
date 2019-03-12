<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubAspekNilai extends Model
{
	protected $guarded = ['id'];
	
    public function penilaians(){
    	return $this->hasMany('App\Penilaian');
    }
    public function penilaian(){
    	return $this->hasOne('App\Penilaian');
    }
    public function aspek_nilai(){
    	return $this->belongsTo('App\AspekNilai');
    }
}
