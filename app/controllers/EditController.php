<?php



class EditController extends BaseController {

    public function __construct() {
        parent::__construct();

        $lang = $this->mandant->sprache ? $this->mandant->sprache : 'de'; // fallback deutsch

        $t = $this->tabs = array();

        $t["auswahl"] = array(
            "text" => $lang === 'en' ? "Template" : "Vorlage",
            "href" => '/auswahl',
            "display" => 1
        );

        $t["edit"] = array(
            "text" => $lang === 'en' ? "Design" : "Gestaltung",
            "href" => '/gestaltung',
            "display" => Session::has('vorlage') || Request::is('gestaltung')
        );


        $download_only = 0;
        if(Session::has('vorlage')){
            $vorlage = Session::get('vorlage');
            if($vorlage->download_only){
                $download_only = 1;
            }
        }

        if (!Session::get('anschrift_fix') && Session::has('data')) {
            $t["anschrift"] = array(
                "text" => $lang === 'en' ? "Address" : "Anschrift",
                "href" => '/anschrift',
                "display" => Session::has('vorlage') || Request::is('anschrift')
            );
        }



        if (Session::has('anschrift_vorhanden') || Request::is('bestellung')) {
            $t["order"] = array(
                "text" => $lang === 'en' ? "Complete order" : "Bestellung",
                "href" => '/bestellung',
                "display" => Session::has('data')
            );
        }

        View::share('tabs', $t);
    }


    public function edit() {
        if (!Session::has('user')) {
            return Redirect::to('/');
        }

        $id = Input::get('templateid');
        if ($id) {
            $vorlage = Vorlage::where('id', '=', $id)
                ->where('mandant_id', '=', $this->mandant->id)
                ->first();
        } else {
            $vorlage = Session::get('vorlage');
        }
        if ($vorlage) {
            Session::put('vorlage', $vorlage);
            $with = ['vorlage' => $vorlage];

            if (Session::has('data')) {
                $with['input'] = Session::get('data')->input;
            }

            // 👉 hier die Prüfung für download_only
            if (!is_null($vorlage->download_only)) {
                $with['download_only'] = $vorlage->download_only;
            }

            $this->activeTab = 'edit';
            return View::make('visitenkarten/edit')->with($with);
        } else {
            return Redirect::to('/auswahl');
        }
    }


    public function save() {
        if (!Session::has('user')) {
            return Redirect::to('/');
        } $data = Input::get('data');
        Session::put('data', json_decode($data));
        return $data;
    }

    public function anschrift() {
        if (!Session::has('user')) {
            return Redirect::to('/');
        } if (!session::has('data')) {
            return Redirect::to('/');
        } $vorlage = Session::get('vorlage');
        $data = Session::get('data');
        $adressen = $vorlage->lieferanschriften()->where('aktiv', '=', 1)->get();
        if (count($adressen) == 0) {
            $adressen = $this->mandant->adressen()->where('aktiv', '=', 1)->get();
        } if ($this->mandant->anschriftFix() && $this->mandant->adressen()->count()) {
            $anschrift = $this->mandant->adressen()->where('aktiv', '=', 1)->first();
            $adresse = array('anschrift_land' => $anschrift->land, 'anschrift_ort' => $anschrift->ort, 'anschrift_plz' => $anschrift->plz, 'anschrift_strasse' => $anschrift->strasse, 'anschrift_name' => $anschrift->name, 'anschrift_abteilung' => $anschrift->abteilung, 'anschrift_firma' => $anschrift->firma);
            Session::put('anschrift', $adresse);
            Session::put('anschrift_fix', 1);
            return Redirect::to('bestellung');
        } else if ($this->mandant->anschrift_manuell) {
            $with = array('adressen' => $adressen, 'radiobuttons' => count($adressen) > 1 && $this->mandant->anschrift_manuell, 'manuell' => $this->mandant->anschrift_manuell, 'title' => 'Anschrift-Auswahl', 'vorlage' => $vorlage, 'data' => $data->output, 'mandant_id' => $this->mandant->id);
            if (Session::has('anschrift')) {
                $with['anschrift'] = Session::get('anschrift');
            } Session::put('anschrift_fix', 0);
            return View::make('visitenkarten/anschrift')->with($with);
        } if ($this->mandant->id == 3) {
            $adressen = [ $adressen[0]];
        } if (count($adressen) == 1) {
            $anschrift = $adressen[0];
            $adresse = array('anschrift_land' => $anschrift->land, 'anschrift_ort' => $anschrift->ort, 'anschrift_plz' => $anschrift->plz, 'anschrift_strasse' => $anschrift->strasse, 'anschrift_name' => $anschrift->name, 'anschrift_abteilung' => $anschrift->abteilung, 'anschrift_firma' => $anschrift->firma);
            Session::put('anschrift', $adresse);
            Session::put('anschrift_fix', 1);
            return Redirect::to('bestellung');
        } else {
            if ($data) {
                return View::make('visitenkarten/anschrift')->with(['adressen' => $adressen, 'data' => $data->output, 'vorlage' => $vorlage, 'manuell' => false, 'anschrift' => Session::get('anschrift')]);
            }
        }
    }

    public function bestellung_post() {
        $keys = array('anschrift_land', 'anschrift_ort', 'anschrift_plz', 'anschrift_strasse', 'anschrift_name', 'anschrift_abteilung', 'anschrift_firma');
        if (Input::has('anschrift_id')) {
            Session::put('anschrift_id', $id = Input::get('anschrift_id'));
            $a = Anschrift::where('id', '=', $id)->get();
            $anschrift = array();
            foreach ($keys as $key) {
                $anschrift[$key] = $a->$id;
            }
        } else {
            Session::forget('anschrift_id');
            $anschrift = array();
            foreach ($keys as $key) {
                $anschrift[$key] = Input::get($key);
            } Session::put('anschrift', $anschrift);
            Session::put('anschrift_vorhanden', 1);
            return $this->bestellung();
        }
    }

    public function bestellung() {
        if (!Session::has('user')) {
            return Redirect::to('/');
        } $data = Session::get('data');
        if ($data) {
            $stueckzahlen = Session::get('vorlage')->preisschema()->first()->preise()->orderBy('menge')->get();
            $with = array('stueckzahlen' => $stueckzahlen, 'data' => $data->output, 'vorlage' => Session::get('vorlage'), 'anschrift' => Session::get('anschrift'), 'extras' => $this->mandant->extras()->orderBy('sort')->get(), 'title' => 'Bestellung', 'anschrift_fix' => Session::get('anschrift_fix'));
            if (!is_null(Session::get('vorlage')->download_only)) {
                $with['download_only'] = Session::get('vorlage')->download_only;
            }
            return View::make('visitenkarten/bestellung')->with($with);
        } return Redirect::to('/auswahl');
    }

    public function auswahl() {
        if (!Session::has('user')) {
            return Redirect::to('/');
        } $this->activeTab = 'auswahl';
        $with = array('vorlagen' => Session::get('user')->vorlagen()->where('deleted', '=', 0)->orderBy('sort')->get(), 'title' => 'Templateauswahl');
        if (Session::has('vorlage')) {
            $with['vorlage'] = Session::get('vorlage');
        } return View::make('misc/auswahl')->with($with);
    }

    public function bestellabschluss() {

        if (!Session::has('user')) {
            return Redirect::to('/');
        } $extras = Input::get('extras') ? Input::get('extras') : array();
        if (!session::has('data')) {
            return Redirect::to('/auswahl');
        } $data = Session::get('data');
        $vorlage = Session::get('vorlage');
        $vorlagenname = $vorlage->name;
        $user = Session::get('user');
        $anschrift = Session::get('anschrift') ?: [];

        $anschrift = array_merge([
            'anschrift_firma'     => '',
            'anschrift_abteilung' => '',
            'anschrift_name'      => '',
            'anschrift_strasse'   => '',
            'anschrift_plz'       => '',
            'anschrift_ort'       => '',
            'anschrift_land'      => '',
        ], $anschrift);
        $download_only = Input::get('download_only') ? true : false;

        if($download_only){
            $preis = 0;
        }else{
            $preis_ = PreisschemaPreis::where('preisschema_id', '=', $vorlage->preisschema_id)->where('menge', '=', Input::get('menge'))->get();
            $preis = $preis_[0]->preis;
        }

        foreach ($extras as $e => $yes) {
            if ($yes) {
                $extra = Extra::where('mandant_id', '=', $this->mandant->id)->where('name', '=', $e)->get();
                $preis += $extra[0]->price;
            }
        }




        if(!$anschrift['anschrift_name']){
            $anschrift['anschrift_name'] = "";
        }
        if(!$anschrift['anschrift_land']){
            $anschrift['anschrift_land'] = "";
        }

        $o = array('mandant_id' => $this->mandant->id, 'vorlage_id' => $vorlage->id, 'status_id' => 1,'freitext1' => $vorlagenname, 'user_id' => Session::get('user')->id, 'input' => json_encode($data->input), 'output' => json_encode($data->output), 'menge' => $menge = Input::get('menge'), 'email' => $email = $data->input->email, 'name' => $data->input->fullName, 'ref' => Str::random(16), 'preis' => $preis, 'anschrift_id' => null, 'anschrift_firma' => $anschrift['anschrift_firma'], 'anschrift_abteilung' => $anschrift['anschrift_abteilung'], 'anschrift_name' => $anschrift['anschrift_name'], 'anschrift_strasse' => $anschrift['anschrift_strasse'], 'anschrift_plz' => $anschrift['anschrift_plz'], 'anschrift_ort' => $anschrift['anschrift_ort'], 'anschrift_land' => $anschrift['anschrift_land'], 'papier' => $this->mandant->papier);
        if (Session::has('anschrift_id')) {
            $o['anschrift_id'] = Session::get('anschrift_id');
        }

        if($download_only){
            $o['status_id'] = 5;
        }

        if($user->confirm==1 && !$download_only){
            $o['status_id'] = 2;
        }


        $order = Order::create($o);
        Protokoll::create(array('order_id' => $order->id, 'text' => 'Bestellung wurde aufgegeben'));
        foreach ($extras as $e => $v) {
            $x = Extra::where('mandant_id', '=', $this->mandant->id)->where('name', '=', $e)->first();
            if ($x) {
                OrderExtra::create(array('mandant_id' => $this->mandant->id, 'order_id' => $order->id, 'name' => $e, 'price' => $x->price));
            } else {
                return 'error' . $e;
            }
        } $protocol = getenv('HTTPS') ? 'https' : 'http';
        $statusUrl = $protocol . '://' . getenv('HTTP_HOST') . '/bestellstatus/' . $order->ref;
        $detailsUrl = $protocol . '://' . getenv('HTTP_HOST') . '/verwaltung/details?id=' . $order->id;
        $with = array('order' => $order, 'tabs' => array("auswahl" => array("text" => "Vorlage", "href" => '/auswahl', "display" => 1,)), 'data' => $data->output, 'vorlage' => $vorlage, 'statusUrl' => $statusUrl, 'title' => 'Bestellung abgeschlossen', 'user' => $user, 'mandantid' => $this->mandant->id);
        $with2 = array('order' => $order, 'email' => $email, 'statusUrl' => $statusUrl, 'mandant' => $mandant = $this->mandant, 'detailsUrl' => $detailsUrl);
        $infotext = MandantExtra::where('mandant_id', '=', $this->mandant->id)->where('key', '=', 'order_infotext')->first();
        if ($infotext) {
            $with['infotext'] = $with2['infotext'] = $infotext->value;
        } if ($user->sendmail) {


            if (0 && Config::get('app.debug')) {
                $from = $to = 'jakober@bairle.de';
                $to_name = 'Egon Schmid';
            } else {
                $from = $mandant->email;
                if($mandant->id==1){
                    $from = 'info@bairle.de';
                }

                $to = $data->input->email;

                $to_name = $data->input->fullName;


                
            } for ($i = 0; $i < 3; $i++) {
                try {
                    if(!$download_only){
                    if($this->mandant->id == 2 || $this->mandant->id == 3){
                        Mail::send('emails/bestellbestaetigung_ingenics', $with2, function($message) use($from, $to, $to_name) {
                            $message->from($from, 'Visitenkarten Online');
                            $message->to($to, $to_name)->subject('Die Bestellung für Ihre Visitenkarten ist eingegangen');
                        });
                    }else{
                        $isEn = ($this->mandant->sprache === 'en');
                        $subject = $isEn
                            ? 'Your business card order has been received'
                            : 'Die Bestellung für Ihre Visitenkarten ist eingegangen';

                        Mail::send(
                            'emails/bestellbestaetigung',
                            array_merge($with2, [
                                'isEn' => $isEn,                 // ✅ Flag für die View
                                'lang' => $this->mandant->sprache // optional, falls du’s brauchst
                            ]),
                            function($message) use ($from, $to, $to_name, $subject) { // <-- $subject hier rein!
                                $message->from($from, 'Visitenkarten Online');
                                $message->to($to, $to_name)->subject($subject);
                            }
                        );
                    }
                    }


                    break;
                } catch (Exception $e) {

                }
            }
        } if (0 && Config::get('app.debug')) {
            $from = $to = 'jakober@bairle.de';
            $to_name = 'Egon Schmid';
        } else {
        	$userdata = DB::table('users')->where('id', Session::get('user')->id)->first();

			$usermail = $userdata->confirm_mail;
            $from = $mandant->email;
            if($mandant->id==1){
                $from = 'info@bairle.de';
            }
			//$to = $mandant->email;
            if($usermail!=""){
            	$to = $usermail;
                $to = 'jakober@bairle.de';
            }else{
            	$to = $mandant->email;
            }

            $to_name = $mandant->email_name;
        }
       /* for ($i = 0; $i < 3; $i++) {*/
            try {
                if(!$download_only){
                Mail::send('emails/neue_bestellung', $with2, function($message) use($from, $to, $to_name, $menge, $data) {
                    $message->from($from, 'Visitenkarten Online');
                    $message->to($to, $to_name)->subject('Bestellung von ' . $menge . ' Visitenkarten für ' . $data->input->fullName);
                });
                }
                /*break;*/
            } catch (Exception $e) {
                throw $e;
            }
        /*}*/
        Session::forget('data');
        Session::forget('vorlage');
        Session::forget('anschrift_vorhanden');
        Session::forget('anschrift_fix');

        if($download_only){
        $pdfUrl = URL::to('order/'.$order->id.'/pdf/3');
        $with['pdfUrl'] = $pdfUrl;

        $pdfUrl2 = URL::to('order/'.$order->id.'/pdf');
        $with['pdfUrl2'] = $pdfUrl2;
        }

        return View::make('visitenkarten/bestellabschluss')->with($with);
        //return json_encode(Session::get('data'));
    }

    public function pdf($id, $beschnitt = 0)
    {
        $order = Order::findOrFail($id);

        // PDF erstellen
        $pdf = $order->createPDF(false, 0, $beschnitt); // oder true für Druckbogen-Modus
        $filename = 'visitenkarten-' . $order->ref . '.pdf';

        if (ob_get_length()) { @ob_end_clean(); }

        // PDF-Inhalt als String holen (wenn das fehlschlägt, wird unten NICHT aktualisiert)
        $content = $pdf->Output($filename, 'S');

        // <<< Hier das gewünschte Update nach erfolgreicher PDF-Erzeugung >>>
        $order->status_id = 5;
        $order->menge = 1;
        $order->preis = 4;
        $order->save();

        return Response::make($content, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }



}
