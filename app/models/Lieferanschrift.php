<?php

class Lieferanschrift extends Eloquent {

    protected $table = 'lieferanschriften';

    public function toHTML() {
        $a = array();
        $a[] = htmlspecialchars($this->firma);

        if ($this->abteilung) {
            $a[] = htmlspecialchars($this->abteilung);
        }
        $a[] = htmlspecialchars($this->strasse);
        $a[] = $this->plz . ' ' . htmlspecialchars($this->ort);
        $a[] = htmlspecialchars($this->land);
        return implode('<br />',$a);
    }

}
