<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agency_id','first_name', 'last_name', 'contact_no','address','image','status','role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function agency()
    {
        return $this->belongsTo('App\Agency');   
    }

    public function role()
    {
        return $this->belongsTo('App\Role');   
    }

    public function isAdministrator()
    {
        return $this->attributes['role_id'] == 1;
    }

    public function collections()
    {
        return $this->hasMany('App\Collection','collector_id');   
    }

    public function debts()
    {
        return $this->hasMany('App\Debt','debtor_id');
    }

    public function productsOnHand()
    {
        return $this->hasMany('App\ProductOnHand','collector_id');
    }

    public function productsDisburse()
    {
        return $this->hasMany('App\ProductDisburse','collector_id');
    }

    public function productsReceive()
    {
        return $this->belongsTo('App\ProductDisburse','debtor_id');
    }
    
    public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();
        return $this->api_token;
    }
}
