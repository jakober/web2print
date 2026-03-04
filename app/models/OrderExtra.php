<?php

class OrderExtra extends Eloquent {
    protected $table = 'orders_extras';
    public static $unguarded = true;
    public $timestamps = false;
}