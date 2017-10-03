<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
	protected $table = 'collection_detail';
    protected $fillable = [
        'collector_id', 'debt_it', 'amount','notes',
    ];

    public function collector()
    {
    	return $this->belongsTo('App\User','collector_id');
    }

    public function debt()
    {
    	return $this->belongsTo('App\Debt');
    }
    
}
