<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
	protected $table = 'collection_detail';
    protected $fillable = [
        'collector_id', 'deb_it', 'amount','notes',
    ];
    
}
