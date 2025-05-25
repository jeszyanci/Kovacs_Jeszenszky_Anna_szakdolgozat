<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    // table név, id, create_at nem kell, mert automatikus (ha elnevezések egyeznek) ..

    protected $attributes = [
        'buyername'  => "",
        'buyerphone' => "",
        'details'    => "",
        'price'      => 0,
        'state'      => 0,
        'deadline'   => "",
    ];
    
    public function __construct($orderData) {
        $this->buyername  = $orderData->buyername;
        $this->buyerphone = $orderData->buyerphone;
        $this->details    = $orderData->details;
        $this->price      = $orderData->price;
        $this->state      = $orderData->state;
        $this->deadline   = $orderData->deadline;
    }
}
