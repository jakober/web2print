<?php

class Vorlage extends Eloquent {
    protected $table = 'vorlagen';
    public static $unguarded = true;


    public function storageFolder() {
        return storage_path().'/vorlagen/' . $this->folder . '';
    }

    public function pdfTemplate() {
        return $this->storageFolder() . '/template.pdf';
    }

    public function lieferanschriften() {
        return $this->belongsToMany('Lieferanschrift','vorlagen_lieferanschriften','vorlage_id','anschrift_id');
    }
    
    public function vorlagen() {
        return $this->belongsTo('Vorlagen');

    }

    public function druckbogen() {
        return $this->belongsTo('Druckbogen');
    }

    public function preisschema() {
        return $this->belongsTo('Preisschema');
    }

}