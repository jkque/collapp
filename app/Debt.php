<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
	protected $table = 'debt';
    protected $fillable = [
        'debtor_id', 'principal_amount', 'term','details', 'status',
    ];

    public function debtor()
    {
    	return $this->belongsTo('App\User','debtor_id');
    }

    public function collections()
    {
    	return $this->hasmany('App\Collection');
    }
    
}
