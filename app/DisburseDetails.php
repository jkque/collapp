<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisburseDetails extends Model
{
	protected $table = 'prod_disburse_details';
    protected $fillable = [
        'pd_id', 'product_id', 'quantity',
    ];
    public $timestamps = false;

    public function productDisburse()
    {
    	return $this->belongsTo('App\ProductDisburse','pd_id');
    }

    public function product()
    {
    	return $this->belongsTo('App\Product');
    }

}
