<?php

class HomeController extends BaseController {
    /*
      |--------------------------------------------------------------------------
      | Default Home Controller
      |--------------------------------------------------------------------------
      |
      | You may wish to use controllers instead of, or in addition to, Closure
      | based routes. That's great! Here is an example controller method to
      | get you started. To route to this controller, just add the route:
      |
      |	Route::get('/', 'HomeController@showWelcome');
      |
     */

    public function startseite() {
        if ($this->mandant == null) {
            return View::make('start/mandantenauswahl')->with('mandanten', Mandant::get());
        }

        if (Session::has('user')) {
            $user = Session::get('user');
            if ($user->gruppe_id == 1) {
                return Redirect::to('/auswahl');
            } else {
                return Redirect::to('/verwaltung/uebersicht');
            }
        }

        return View::make('start/login')->with('tabs', array())->with('title','Login');
    }

}
