<?php

class UserVorlage extends Eloquent {
    public static $unguarded = true;
    protected $table = 'user_vorlagen';

    public function user() {
        return $this->belongsTo('User');
    }

    public function vorlage() {
        return $this->belongsTo('Vorlage');
    }

}
