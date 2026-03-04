<?php

class Preisschema extends Eloquent {
    protected $table = 'preisschema';
    public static $unguarded = true;

    public function preise() {
        return $this->hasMany('PreisschemaPreis');
    }
}
