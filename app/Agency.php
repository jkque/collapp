<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Model
{
	use SoftDeletes;
	protected $table = 'agency';
    protected $fillable = [
        'code', 'name', 'billing_address','contact_number',
    ];

    public function members()
    {
    	return $this->hasMany('App\User');
    }

    public function collectors()
    {
        return $this->members()->where('role_id', 2);
    }

    public function stores()
    {
        return $this->members()->where('role_id', 3);
    }

}
