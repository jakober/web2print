<?php

class VerwaltungsController extends BaseController {

    public function __construct() {
        parent::__construct();

        $t = $this->tabs = array(
            "uebersicht" => array(
                "text" => "Aktuelle Bestellungen",
                "href" => '/verwaltung/uebersicht',
                "display" => 1
            ),
            "details" => array(
                "text" => "Bestellungen",
                "href" => '/verwaltung/uebersicht',
                "display" => 0
            ),
            "historie" => array(
                "text" => "Bestell-Historie",
                "href" => '/verwaltung/historie',
                "display" => 1
            ),
            "benutzer" => array(
                "text" => "Benutzer",
                "href" => '/verwaltung/benutzer',
                "display" => 1
            ),
            "einstellungen" => array(
                "text" => "Einstellungen",
                "href" => '/verwaltung/einstellungen',
                "display" => 1
            )
        );
        View::share('tabs', $t);
    }
    public function pw(){
        $password = 'iVu@kNbDT-admin';
        $hashedPassword = Hash::make($password);

        return $hashedPassword;
    }
    public function uebersicht() {
        if (Session::has('user') && Session::get('user')->gruppe_id == 2) {
            $perpage = 20;
            if(isset($_GET["page"])){
                $page = $_GET["page"];
            }else{
                $page = 0;
            }
            $skip = $perpage*$page;
            $take = $perpage;
            $orders = Order::where('mandant_id', '=', $this->mandant->id)->where('status_id', '<', 6)->orderBy('status_id')->orderBy('id', 'DESC')->skip($skip)->take($take)->get();
            $orders_count = Order::where('mandant_id', '=', $this->mandant->id)->where('status_id', '<', 6)->orderBy('status_id')->orderBy('id', 'DESC')->count();
            $pages = floor($orders_count/$perpage);
            $mandant = DB::table('mandanten')->where('id', $this->mandant->id)->get();

            $delete_date = date('Y-m-d H:i:s', strtotime('-2 year', time()));
            $orders_delete = Order::where('mandant_id', '=', $this->mandant->id)->where('created_at','<',$delete_date)->delete();
          

            return View::make('verwaltung/uebersicht')->with('orders', $orders)->with('mandanten', $mandant)->with('pages', $pages);
        }
        return Redirect::to('/');
    }

    public function historie() {
        if (Session::has('user') && Session::get('user')->gruppe_id == 2) {

            $perpage = 20;
            if(isset($_GET["page"])){
                $page = $_GET["page"];
            }else{
                $page = 0;
            }
            $skip = $perpage*$page;
            $take = $perpage;


            $orders = Order::where('mandant_id', '=', $this->mandant->id)->where('status_id', '=', 7)->orderBy('id', 'DESC')->skip($skip)->take($take)->get();
            $orders_count = Order::where('mandant_id', '=', $this->mandant->id)->where('status_id', '=', 7)->orderBy('id', 'DESC')->count();
            $pages = floor($orders_count/$perpage);

            return View::make('verwaltung/uebersicht')->with('orders', $orders)->with('pages', $pages);
        }
        return Redirect::to('/');
    }

    public function details() {
        $tabs = array(
            "uebersicht" => array(
                "text" => "Aktuelle Bestellungen",
                "href" => '/verwaltung/uebersicht',
                "display" => 1
            ),
            "historie" => array(
                "text" => "Bestell-Historie",
                "href" => '/verwaltung/historie',
                "display" => 1
            ),
            "details" => array(
                "text" => "Bestell-Details",
                "href" => '#',
                "display" => 1
            ),
            "benutzer" => array(
                "text" => "Benutzer",
                "href" => '/verwaltung/benutzer',
                "display" => 1
            ),
            "einstellungen" => array(
                "text" => "Einstellungen",
                "href" => '/verwaltung/einstellungen',
                "display" => 1
            )
        );

        if (Session::has('user') && Session::get('user')->gruppe_id == 2 && Input::has('id')) {
            $id = Input::get('id');
            $order = Order::where('id', '=', $id)->first();

            if ($order) {
                if (Input::has('templateid')) {
                    $vorlage = Vorlage::where('id', '=', Input::get('templateid'))->first();
                } else {
                    $vorlage = $order->vorlage()->first();
                }
                $extras = $this->mandant->extras()->orderBy('sort')->get();

                $extras_o = OrderExtra::where('order_id', '=', $order->id)->select('name')->get();
                $keys = array();
                foreach ($extras_o as $k) {
                    $keys[$k['name']] = 1;
                }
                foreach ($extras as &$e) {
                    if (isset($keys[$e->name])) {
                        $e->checked = 1;
                    }
                }
                //return $extras;

                $with = array(
                    'order' => $order,
                    'protokoll' => $order->protokoll()->where('created_at','<',new DateTime())->get(),
                    'vorlage' => $vorlage,
                    'status' => $order->status()->first(),
                    'tabs' => $tabs,
                    'stueckzahlen' => $vorlage->preisschema()->first()->preise()->orderBy('menge')->get(),
                    'extras' => $extras,
                    'title' => 'Details zu Bestellung "' . $order->name . '"'
                );
                if (Input::has('templateid') && Input::get('templateid') != $order->vorlage_id) {
                    $with['edit'] = 1;
                }

                if ($order->status_id < 3) {
                    return View::make('verwaltung/details')->with($with);
                } else {
                    $with['extras'] = $extras_o;
                    return View::make('verwaltung/details_nur_anzeigen')->with($with);
                }
            }
        }
        return Redirect::to('/');
    }

    public function uebersicht_2() {
        if (Session::has('user') && Session::get('user')->gruppe_id == 2) {

            $svgs = array();
            $vorlagen = $this->mandant->vorlagen()->select('folder', 'seiten')->distinct()->get();
            $vorlagenB = $this->mandant->vorlagen()->select('id', 'folder')->get();
            $configs = array();

            foreach ($vorlagen as $v) {
                $a = array();
                for ($i = 0; $i < $v->seiten; $i++) {
                    $a[] = file_get_contents(storage_path() . '/vorlagen/' . $v->folder . '/page' . ($i + 1) . '.svg');
                }
                $svgs[$v->folder] = $a;
            }
            foreach ($vorlagenB as $v) {
                $folders[$v->id] = $v->folder;
                $c = file_get_contents(public_path() . '/visika/' . $v->id . '/config.json');
                $configs[$v->id] = json_decode($c);
            }
            $orders = Order::where('mandant_id', '=', $this->mandant->id)->where('status_id', '=', 1)->get();
            foreach ($orders as $o) {
                $o->input = json_decode($o->input);
                $o->output = json_decode($o->output);
            }
            return View::make('verwaltung/uebersicht')->with('svgs', $svgs)->with('orders', $orders)->with('vorlagen', $folders)->with('configs', $configs);
        }
        return Redirect::to('/');
    }

    public function freigabe() {
        if (Session::has('user') && Session::get('user')->gruppe_id == 2) {
            $id = Input::get('id');

            $order = Order::where('id', '=', $id)->where('mandant_id', '=', $this->mandant->id)->first();
            $order->status_id = 2;
            $order->menge = Input::get('menge');
            $order->save();

            Protokoll::create(
                    array(
                        'order_id' => $order->id,
                        'text' => 'Bestellung wurde freigegeben'
                    )
            );
            return array();
        }
    }

    public function loeschen() {
        if (Session::has('user') && Session::get('user')->gruppe_id == 2) {
            $id = Input::get('id');
            $order = Order::where('id', '=', $id)->where('mandant_id', '=', $this->mandant->id)->first();
            $order->status_id = 6;
            $order->save();
            return array();
        }
    }
    
    
    public function saveImage() {
        $bild = Input::get('bild');
       echo $bild;
       
       $contents= file_get_contents($bild);
        setlocale(LC_TIME, 'de_DE');
        $savename = strftime("%Y-%m-%d_%H-%M-%S");
        
        $savefile = fopen("$savename.jpg", "w");
        fwrite($savefile, $contents);
        fclose($savefile);
    }
    
    
    public function bestellung_kopieren(){
        $id = Input::get('id');
        
        $timestamp = time();
        $date = date('Y-m-d H:i',$timestamp);
        
        $order = Order::where('id', '=', $id)->first();


        
        
        $id = DB::table('orders')->insertGetId(
            array(
            'mandant_id' => $order->mandant_id, 
            'vorlage_id' => $order->vorlage_id, 
            'status_id' => 1, 
            'name' => $order->name, 
            'freitext1' => $order->freitext1, 
            'user_id' => $order->user_id, 
            'anschrift_id' => $order->anschrift_id, 
            'anschrift_firma' => $order->anschrift_firma, 
            'anschrift_abteilung' => $order->anschrift_abteilung,
            'anschrift_name' => $order->anschrift_name, 
            'anschrift_strasse' => $order->anschrift_strasse, 
            'anschrift_plz' => $order->anschrift_plz, 
            'anschrift_ort' => $order->anschrift_ort, 
            'anschrift_land' => $order->anschrift_land, 
            'email' => $order->email, 
            'input' => $order->input, 
            'output' => $order->output, 
            'menge' => $order->menge, 
            'preis' => $order->preis, 
            'ref' => $order->ref, 
            'created_at' => $date, 
            'updated_at' => $date
            )
        );
        
        DB::table('protokoll')->insert(
            array(
            'order_id' => $id, 
            'text' => 'Bestellung wurde aufgegeben', 
            'created_at' => $date, 
            'updated_at' => $date
            )
        );
        
        
       
        
        return Redirect::to('verwaltung/details?id='.$id);
        
    }

    public function updateOrder() {
        if (Session::has('user') && Session::get('user')->gruppe_id == 2) {
            $id = Input::get('id');
            $order = Order::where('id', '=', $id)->where('mandant_id', '=', $this->mandant->id)->first();
            if ($order) {
                $data = json_decode(Input::get('data'));
                $order->input = json_encode($data->input);
                $order->output = json_encode($data->output);
                $order->name = $data->input->fullName;
                $order->save();
                return array();
            }
        }
    }

    public function updateOrder2() {
        if (Session::has('user') && Session::get('user')->gruppe_id == 2) {
            $id = Input::get('id');
            $extras = Input::get('extras');

            $order = Order::where('id', '=', $id)->where('mandant_id', '=', $this->mandant->id)->first();
            if ($order) {
                $order->menge = Input::get('menge');
                if (Input::has('anschrift_firma')) {
                    $order->anschrift_land = Input::get('anschrift_land');
                    $order->anschrift_ort = Input::get('anschrift_ort');
                    $order->anschrift_plz = Input::get('anschrift_plz');
                    $order->anschrift_strasse = Input::get('anschrift_strasse');
                    $order->anschrift_name = Input::get('anschrift_name');
                    $order->anschrift_abteilung = Input::get('anschrift_abteilung');
                    $order->anschrift_firma = Input::get('anschrift_firma');
                }
                $order->vorlage_id = Input::get('vorlageId');

                if (Input::has('extras')) {
                    OrderExtra::where('order_id', '=', $id)->delete();
                    $extras = Input::get('extras');
                    foreach ($extras as $e) {
                        $x = Extra::where('mandant_id', '=', $this->mandant->id)->where('name', '=', $e)->first();
                        OrderExtra::create(
                                array(
                                    'mandant_id' => $this->mandant->id,
                                    'order_id' => $order->id,
                                    'name' => $e,
                                    'price' => $x->price
                                )
                        );
                    }
                }
                $order->save();
                return array();
            }
        }
    }

    public function benutzer() {

        if (Input::get('action') == 'delete') {
            $id = Input::get('id');
            User::where('id', '=', $id)->where('mandant_id', '=', $this->mandant->id)->delete();
        }
        $with = array(
            'users' => $this->mandant->users()->orderBy('gruppe_id')->orderBy('username')->get(),
            'title' => 'Benutzerverwaltung'
        );
        if (Session::has('message')) {
            $with['message'] = Session::get('message');
        }
        return View::make('verwaltung/benutzer')->with($with);
    }

    public function neuerBenutzer() {
        $this->tabs['neuerBenutzer'] = array(
            "text" => "Neuer Benutzer",
            "href" => '#',
            "display" => 1
        );

        $gruppen = Gruppe::where('id', '<', 3)->lists('bezeichnung', 'id');
        $vorlagen = $this->mandant->vorlagen()->get();
        $errors = array();

        $with = array(
            'gruppen' => $gruppen,
            'tabs' => $this->tabs,
            'vorlagen' => $vorlagen,
            'gruppe' => 0,
            'title' => 'Neuen Benutzer anlegen'
        );

        if (Input::has('gruppe')) {
            // POST
            $username = Input::get('username');
            $name = Input::get('name');
            $with['gruppe'] = $gruppe = Input::get('gruppe');
            $password = Input::get('password') . '';


            if ($username == '') {
                $with['error'] = 'Kein Benutzername angegeben';
                return View::make('verwaltung/neuer_benutzer')->with($with);
            }
            $user = User::where('username', '=', $username)->where('mandant_id', '=', $this->mandant->id)->first();

            $with['username'] = $username;
            $with['name'] = $name;
            $with['password'] = $password;

            if ($user != null) {
                $with['error'] = 'Der Benutzer existiert bereits';
                return View::make('verwaltung/neuer_benutzer')->with($with);
            } else {

                if ($password == '') {
                    $with['error'] = 'Bitte ein Kennwort vergeben';
                    return View::make('verwaltung/neuer_benutzer')->with($with);
                }

                $vorlageIds = Input::get('vorlagen');
                if ($vorlageIds == null || !is_array($vorlageIds) || count($vorlageIds) == 0) {
                    $with['error'] = 'Bitte mindestens eine Vorlage vergeben';
                    return View::make('verwaltung/neuer_benutzer')->with($with);
                }
                $user = new User();
                $user->username = $username;
                $user->name = $name;
                $user->gruppe_id = $gruppe;
                $user->password = Hash::make($password);
                $user->mandant_id = $this->mandant->id;
                $user->save();

                foreach ($vorlageIds as $id) {
                    UserVorlage::create(array(
                        'user_id' => $user->id,
                        'vorlage_id' => $id
                    ));
                }
                Session::flash('message', 'Benutzer "' . $username . '" wurde angelegt');

                return Redirect::to('/verwaltung/benutzer');
            }
        } else {
            return View::make('verwaltung/neuer_benutzer')->with($with);
        }
    }

    public function benutzerDetails() {
        $user = User::where('id', '=', Input::get('id'))->where('mandant_id', '=', $this->mandant->id)->first();

        $gruppen = Gruppe::where('id', '<', 3)->lists('bezeichnung', 'id');
        $vorlagen = $this->mandant->vorlagen()->get();
        $uv = $user->vorlagen()->get();

        $this->tabs['benutzerDetails'] = array(
            "text" => "Benutzerdetails",
            "href" => '#',
            "display" => 1
        );

        $uv2 = array();
        foreach ($uv as $v) {
            $uv2[$v->id] = 1;
        }

        $with = array(
            'user' => $user,
            'gruppen' => $gruppen,
            'vorlagen' => $vorlagen,
            'uv' => $uv2,
            'tabs' => $this->tabs,
            'title' => 'Benutzerdetails'
        );

        if (Input::has('gruppe')) {
            $with['username'] = $user->username = $username = Input::get('username');
            $with['name'] = $user->name = $name = Input::get('name');
            $with['gruppe'] = $user->gruppe_id = $gruppe = Input::get('gruppe');
            $with['sendmail'] = $user->sendmail = $sendmail = Input::has('sendmail') ? 1 : 0;
			
			$with['confirm'] = $user->confirm = $confirm = Input::has('confirm') ? 1 : 0;
			
			$with['confirm_mail'] = $user->confirm_mail = $confirm_mail = Input::get('confirm_mail');
		
            $password = Input::get('password') . '';

            if ($password != '') {
                $user->password = Hash::make($password);
            }

            if ($username == '') {
                $with['error'] = 'Kein Benutzername angegeben';
                return View::make('verwaltung/benutzer_details')->with($with);
            }
            if ($user->username != $username) {
                $user_test = User::where('username', '=', $username)->where('mandant_id', '=', $this->mandant->id)->first();
                if ($user_test) {
                    $with['error'] = 'Es existiert bereits ein anderer Benutzer mit diesem Namen';
                    return View::make('verwaltung/benutzer_details')->with($with);
                }
                $user->username = $username;
            }

            $vorlageIds = Input::get('vorlagen');
            if ($vorlageIds == null || !is_array($vorlageIds) || count($vorlageIds) == 0) {
                $with['error'] = 'Bitte mindestens eine Vorlage vergeben';
                return View::make('verwaltung/benutzer_details')->with($with);
            }

            UserVorlage::where('user_id', '=', $user->id)->delete();

            foreach ($vorlageIds as $id) {
                UserVorlage::create(array(
                    'user_id' => $user->id,
                    'vorlage_id' => $id
                ));
            }
            $user->save();

            Session::flash('message', 'Benutzer "' . $username . '" wurde geändert');

            return Redirect::to('/verwaltung/benutzer');
        }
        //return $uv2;

        return View::make('verwaltung/benutzer_details')->with($with);
    }

    public function selectTemplate() {
        $tabs = array(
            "uebersicht" => array(
                "text" => "Bestellungen",
                "href" => '/verwaltung/uebersicht',
                "display" => 1
            ),
            "template" => array(
                "text" => "Template-Auswahl",
                "href" => '#',
                "display" => 1
            ),
            "benutzer" => array(
                "text" => "Benutzer",
                "href" => '/verwaltung/benutzer',
                "display" => 1
            )
        );

        if (!Session::has('user')) {
            return Redirect::to('/');
        }

        $order = Order::where('id', '=', Input::get('orderid'))->first();

        $with = array(
            'vorlagen' => $this->mandant->vorlagen()->where('deleted','!=',1)->get(),
            'title' => 'Templateauswahl',
            'vorlage' => $order->vorlage()->first(),
            'tabs' => $tabs,
            'order' => $order
        );

        return View::make('misc/auswahl')->with($with);
    }

    public function einstellungen() {

        if(Input::has('email')) {
            $this->mandant->email = Input::get('email');
            $this->mandant->name = Input::get('name');
            $this->mandant->save();
        }
        return View::make('verwaltung/einstellungen')->with(
                        array(
                            'mandant' => $this->mandant
                        )
        );
    }

    /*    public function auswahl() {
      if (!Session::has('user')) {
      return Redirect::to('/');
      }
      $this->activeTab = 'auswahl';

      $with = array(
      'vorlagen' => Session::get('user')->vorlagen()->get(),
      'title' => 'Templateauswahl'
      );

      if (Session::has('vorlage')) {
      $with['vorlage'] = Session::get('vorlage');
      }
      return View::make('misc/auswahl')->with($with);
      }
     */
}
