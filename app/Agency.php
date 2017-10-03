<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
	use SoftDeletes;
	protected $table = 'agency';
    protected $fillable = [
        'code', 'name', 'billing_address','contact_number',
    ];
}
