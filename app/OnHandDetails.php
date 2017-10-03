<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OnHandDetails extends Model
{
	protected $table = 'prod_on_hand_details';
    protected $fillable = [
        'poh_id', 'product_id', 'quantity',
    ];
    public $timestamps = false;

    public function productsOnHand()
    {
    	return $this->belongsTo('App\ProductOnHand','poh_id');
    }

    public function product()
    {
    	return $this->belongsTo('App\Product');
    }


}
