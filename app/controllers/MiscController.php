<?php

class MiscController extends BaseController {

    public function login() {

        if (Input::has('username') || Input::has('key')) {



            //$user = new User();
            //$user->mandant_id = $this->mandant->id;
            //$user->username = $login;
            //$user->gruppe_id = 1;
            //$user->password = Hash::make($password);
            //$user->save();
        if (Input::has('key')) {
            $user = User::where('mandant_id', '=', $this->mandant->id)->where('loginkey', '=', Input::get('key'))->first();
        }else{
            $login = Input::get('username');
            $password = Input::get('password');
            $user = User::where('mandant_id', '=', $this->mandant->id)->where('username', '=', $login)->first();

        }


            if ($user != null /*&& Hash::check($password, $user->password)*/) {
                Session::put('user', $user);
                if ($user->gruppe_id == 1) {
                    return Redirect::to('/auswahl');
                }
                if ($user->gruppe_id == 2) {
                    return Redirect::to('/verwaltung/uebersicht');
                }
            }else{
                
            }
            if ($user == null) {
                $error = 'Falscher Benutzername!';
            } else {
                $error = 'Falsches Kennwort!';
            }

            return View::make('start/login')->with('tabs', array())->with('title', 'Login');
        }
        return Redirect::to('/');
    }

    public function logout() {
        Session::flush();
        return Redirect::to('/');
    }

    public function statusAnsicht($key) {
        $order = Order::where('ref', '=', $key)->first();
        if ($order == null || $order->mandant_id!=$this->mandant->id) {
            return Redirect::to('/');
        }

        $with = array(
            'order' => $order,
            'protokoll' => $order->protokoll()->where('created_at','<',new DateTime())->get(),
            'vorlage' => $order->vorlage()->first(),
            'status' => $order->status()->first(),
            'tabs' => array(),
            'title' => 'Status zur Bestellung #' . $order->id
        );

        return View::make('misc/statusansicht')->with($with);
    }

}
