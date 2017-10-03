<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;
	protected $table = 'product';
    protected $fillable = [
        'code', 'name', 'description','price', 'unit_id',
    ];

    public function unit()
    {
    	return $this->belongsTo('App\Unit');
    }

    public function onHandDetails()
    {
    	return $this->hasMany('App\OnHandDetails');
    }

    public function disburseDetails()
    {
    	return $this->hasMany('App\DisburseDetails');
    }
    
}
