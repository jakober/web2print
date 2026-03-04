<?php

class AbrechnungsController extends Controller {

    public function uebersicht() {

        $m = DB::table('mandanten')
                ->join('orders', 'orders.mandant_id', '=', 'mandanten.id')
                ->where('orders.status_id', '=', 5)
                ->groupBy('mandanten.id')
                ->orderBy('mandanten.id')
                ->select(
                        'mandanten.id', 'mandanten.name', DB::raw('COUNT(mandanten.id) as bestellungen'), DB::raw('SUM(orders.menge) as menge'))
                ->get();

        return $m;
    }

/*    public function bestellungenMandant($id) {
        $orders = DB::table('orders')
                ->join('vorlagen', 'orders.vorlage_id', '=', 'vorlagen.id')
                ->where('orders.mandant_id', '=', $id)
                ->where('orders.status_id', '=', 5)
                ->orderBy('orders.geliefert_am')
                ->select('orders.id', 'orders.lieferschein_id', 'vorlagen.seiten', 'orders.name', 'orders.geliefert_am','orders.anschrift_land', 'orders.menge', 'orders.freitext1', 'orders.created_at', 'orders.preis', 'orders.bestellnummer')
                ->get();

        forEach ($orders as $o) {
            $o->extras = json_decode('{}', true);
            $e = OrderExtra::where('order_id', '=', $o->id)->get();
            foreach ($e as $it) {
                $o->extras[$it->name] = 1;
            }
        }
        $m = Mandant::where('id', '=', $id)->select('id', 'name', 'freitext_feld1_titel')->first()->toArray();

        return array('m' => $m, 'o' => $orders);
    }*/


public function bestellungenMandant($id) {
    $orders = DB::table('orders')
            ->join('vorlagen', 'orders.vorlage_id', '=', 'vorlagen.id')
            ->where('orders.mandant_id', '=', $id)
            ->where('orders.status_id', '=', 5)
            ->where('orders.menge', '!=', 0)
            ->orderBy('orders.created_at') // Wichtig für die Zeit-Logik: nach Erstelldatum sortieren
            ->select(
                'orders.id',
                'orders.lieferschein_id',
                'vorlagen.seiten',
                'orders.name',
                'orders.geliefert_am',
                'orders.anschrift_land',
                'orders.menge',
                'orders.freitext1',
                'orders.created_at',
                'orders.preis',
                'orders.bestellnummer',
                'orders.vorlage_id' // NEU: Wir brauchen die ID zum Prüfen
            )
            ->get();

    $filteredOrders = [];
    $lastSeenFor69 = []; // Speicher für: [Name => Letzter Zeitstempel]

    foreach ($orders as $o) {
        // Logik für Vorlage 69
        if ($o->vorlage_id == 69) {
            $currentTime = strtotime($o->created_at);
            $userName = $o->name;

            // Prüfen, ob dieser Name innerhalb der letzten 30 Min schon dran war
            if (isset($lastSeenFor69[$userName])) {
                $timeDiffMinutes = ($currentTime - $lastSeenFor69[$userName]) / 60;

                if ($timeDiffMinutes <= 1440) {
                    continue;
                }
            }

            // Zeitstempel für diesen Namen aktualisieren
            $lastSeenFor69[$userName] = $currentTime;
        }

        // Falls wir nicht "continue" aufgerufen haben, Bestellung verarbeiten:
        $o->extras = json_decode('{}', true);
        $e = OrderExtra::where('order_id', '=', $o->id)->get();
        foreach ($e as $it) {
            $o->extras[$it->name] = 1;
        }

        $filteredOrders[] = $o;
    }

    $m = Mandant::where('id', '=', $id)
            ->select('id', 'name', 'freitext_feld1_titel')
            ->first()
            ->toArray();

    return array('m' => $m, 'o' => $filteredOrders);
}


}
