<?php

class Protokoll extends Eloquent {

    protected $table = 'protokoll';
    public static $unguarded = true;

    public function order() {
        return $this->belongsTo('Order');
    }

}
