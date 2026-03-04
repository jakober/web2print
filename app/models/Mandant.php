<?php

/**
 * Description of Beitrag
 *
 * @author schmid
 */
class Mandant extends Eloquent {

    protected $table = 'mandanten';
    public static $unguarded = true;

    public function users() {
        return $this->hasMany('User');
    }

    public function extras() {
        return $this->hasMany('Extra');
    }

    public function adressen() {
        return $this->hasMany('Lieferanschrift');
    }

    public function vorlagen() {
        return $this->hasMany('Vorlage');
    }

    public function orders() {
        return $this->hasMany('Order');
    }

    public function stueckzahlen() {
        return $this->hasMany('Stueckzahl');
    }

    public function getCSS() {
        return HTML::style('/css/' . $this->css . '?r='.Config::get('app.revision'));
    }

    public function getLogo() {
        return '<img src="/img/logos/' . $this->logo . '?r='.Config::get('app.revision').'" alt="(Logo)" />';
    }

    public function anschriftFix() {
        if ($this->anschrift_manuell) {
            return false;
        }
        return ($this->adressen()->where('aktiv', '=', 1)->count()) < 2;
    }

}
