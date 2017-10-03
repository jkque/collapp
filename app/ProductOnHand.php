<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOnHand extends Model
{
	use SoftDeletes;
	protected $table = 'products_on_hand';
    protected $fillable = [
        'collector_id', 'remarks',
    ];

    public function collector()
    {
    	return $this->belongsTo('App\User','collector_id');
    }

    public function details()
    {
    	return $this->hasMany('App\OnHandDetails','poh_id');
    }
}
