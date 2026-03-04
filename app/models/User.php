<?php

class User extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    public function gruppe() {
        return $this->belongsTo('Gruppe');
    }

    public function vorlagen() {
        return $this->belongsToMany('Vorlage', 'user_vorlagen', 'user_id', 'vorlage_id');
    }
}
