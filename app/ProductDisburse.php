<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDisburse extends Model
{
	use SoftDeletes;
	protected $table = 'product_disburse';
    protected $fillable = [
        'collector_id', 'debtor_id', 'details',
    ];

    public function collector()
    {
    	return $this->belongsTo('App\User','collector_id');
    }

    public function debtor()
    {
    	return $this->belongsTo('App\User','debtor_id');
    }

    public function details()
    {
    	return $this->hasMany('App\DisburseDetails','pd_id');
    }
}
