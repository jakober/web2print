<?php

class AjaxController extends Controller {

    public function uebersicht() {
        //$m = Mandant::select('id', 'name')->get()->toArray();
        $m = DB::table('mandanten')
                ->join('vorlagen', 'vorlagen.mandant_id', '=', 'mandanten.id')
                ->join('orders', 'orders.vorlage_id', '=', 'vorlagen.id')
                ->join('druckboegen', 'vorlagen.druckbogen_id', '=', 'druckboegen.id')
                ->where('orders.status_id', '=', 2)
                ->whereNull('vorlagen.download_only')
                ->groupBy('druckboegen.id')
                ->orderBy('mandanten.id')
                ->select(
                        DB::raw("druckboegen.seiten AS seiten"), DB::raw("vorlagen.id AS vorlage_id"), DB::raw('druckboegen.name as druckbogen_name'), DB::raw('druckboegen.gruppe_id as gruppe'), DB::raw('druckboegen.id as druckbogen_id'), 'mandanten.id', 'mandanten.name', DB::raw('druckboegen.nx * druckboegen.ny AS vpd'), DB::raw('COUNT(*) as bestellungen'), DB::raw('SUM(orders.menge) as menge'))
                ->get();

        return array(
            "druckboegen" => $m
        );
    }

    public function uebersichtDetails($id) {
        $ids = explode(',', $id);
        $db = Druckbogen::whereIn('id', $ids)->get()->toArray();

        $result = DB::table('druckboegen')
                ->join('vorlagen', 'vorlagen.druckbogen_id', '=', 'druckboegen.id')
                ->join('orders', 'orders.vorlage_id', '=', 'vorlagen.id')
                ->whereIn('druckboegen.id', $ids)
                ->whereIn('orders.status_id', array(2, 3))
                ->select(
                        'orders.id', 'orders.name', 'orders.menge', 'vorlagen.seiten'
                )
                ->orderBy('orders.id')
                ->get();

        foreach ($result as &$r) {
            $r->options = DB::table('orders_extras')
                    ->join('extras', 'extras.name', '=', 'orders_extras.name')
                    ->where('extras.mandant_id', '=', 1)
                    ->where('order_id', '=', $r->id)
                    ->select('extras.text')
                    ->get();
        }

        if (count($db) > 1) {
            $db[0]['seiten'] = 3; // 3 = gemischt
        }

        return array(
            'orders' => $result,
            'druckbogen' => $db[0]
        );
    }

    public function setStatusGedruckt() {
        $order_ids = Input::get('orders');

        $orders = Order::whereIn('id', $order_ids)->select('id')->get();

        $a = array();
        foreach ($orders as $order) {
            $a[] = $order->id;
        }

        DB::table('orders')
                ->whereIn('id', $a)
                ->update(array('status_id' => 4));

        foreach ($a as $id) {
            Protokoll::create(
                    array(
                        'order_id' => $id,
                        'text' => 'Visitenkarte wurde gedruckt'
            ));
        }

        return array();
    }
    
    public function setBestellnummer(){
        $id = Input::get('id');
        $bestellnummer = Input::get('bestellnummer');
         DB::table('orders')
            ->where('id', $id)
            ->update(array('bestellnummer' => $bestellnummer));
    }
    
    
    public function setStatusBerechnet() {
        $timestamp = time();
        $date = date('Y-m-d H:i',$timestamp);
            
        $order_ids = Input::get('orders');
    
        $orders = Order::whereIn('id', $order_ids)->select('id')->get();

        $a = array();
        foreach ($orders as $order) {
            $a[] = $order->id;
            
            DB::table('protokoll')->insert(
            array(
            'order_id' => $order->id, 
            'text' => 'Rechnung wurde erstellt', 
            'created_at' => $date, 
            'updated_at' => $date
            )
            );
            
            
        }
        
        
        
        
        DB::table('orders')
                ->whereIn('id', $a)
                ->update(array('status_id' => 7));

        return array();
    }

    public function lieferscheineUebersicht() {
        //$m = Mandant::select('id', 'name')->get()->toArray();

        $m = DB::table('mandanten')
                        ->join('orders', 'orders.mandant_id', '=', 'mandanten.id')
                        ->where('orders.status_id', '=', 4)
                        ->groupBy('orders.mandant_id')
                        ->select('mandanten.id', 'mandanten.name', DB::raw('COUNT(orders.id) as menge'))->get();
        return $m;
    }

    public function lieferscheineMandant($mandant_id) {
        $m = DB::table('orders')
                        ->where('orders.mandant_id', '=', $mandant_id)
                        ->where('orders.status_id', '=', 4)
                        ->groupBy('orders.anschrift_firma', 'orders.anschrift_abteilung', 'orders.anschrift_name', 'orders.anschrift_strasse', 'orders.anschrift_plz', 'orders.anschrift_ort', 'orders.anschrift_land')
                        ->select('orders.anschrift_firma', 'orders.anschrift_abteilung', 'orders.anschrift_name', 'orders.anschrift_strasse', 'orders.anschrift_plz', 'orders.anschrift_ort', 'orders.anschrift_land', DB::raw('COUNT(orders.id) as menge'), DB::raw('GROUP_CONCAT(orders.id SEPARATOR \',\') AS ids'))->get();
        return $m;
    }

    public function lieferscheineDrucken() {

        $ids = Input::get('order_ids');
        $date = Input::get('date');

        if (count($ids)) {

            $lieferschein = Lieferschein::create(array('liefertermin' => $date));

            $anschrift = Order::where('id', '=', $ids[0])->select('anschrift_firma', 'anschrift_abteilung', 'anschrift_name', 'anschrift_strasse', 'anschrift_plz', 'anschrift_ort', 'anschrift_land')->first()->toArray();
            $orders = Order::whereIn('orders.id', $ids)
                    ->join('vorlagen', 'orders.vorlage_id', '=', 'vorlagen.id')
                    ->select('orders.id', 'orders.name', 'menge', DB::raw('vorlagen.name as vname'), 'orders.created_at', 'orders.bestellnummer')
                    ->get();

            foreach ($orders as &$r) {
                $options = DB::table('orders_extras')
                    ->join('extras', 'extras.name', '=', 'orders_extras.name')
                    ->where('extras.mandant_id', '=', 1)
                    ->where('order_id', '=', $r->id)
                    ->select('extras.text')
                    ->get();

                $a = array();
                foreach ($options as $o) {
                    $a[] = $o->text;
                }
                $r['options'] = $a;

                Protokoll::create(array(
                    'order_id' => $r->id,
                    'text' => 'Visitenkarte wurde geliefert. (Lieferschein-Nummer: ' . $lieferschein->id . ')',
                    'created_at' => $date . ' 12:00:00',
                    'updated_at' => $date . ' 12:00:00'
                ));
            }

            DB::table('orders')->whereIn('id', $ids)->update(array('lieferschein_id' => $lieferschein->id, 'status_id' => 5, 'geliefert_am' => $date));

            return array(
                'anschrift' => $anschrift,
                'orders' => $orders->toArray(),
                'lieferschein' => $lieferschein->id
            );
        }
    }

    public function templates() {
        $m = Mandant::select('id', 'name')->get()->toArray();
        return array(
            "mandanten" => $m
        );
    }

}
