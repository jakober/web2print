<?php

class AdminController extends Controller {
/*
    public function login() {
        return View::make('admin/login');
    }

    public function login_post() {
        $username = Input::get('username');
        $password = Input::get('password');

        if ($username == 'admin' && $password == 'admin') {
            Session::put('user', 1);
            return Redirect::to('/uebersicht');
        }
        if ($username == 'admin2' && $password == 'admin2') {
            Session::put('user', 1);
            return Redirect::to('/uebersicht2');
        }

        return View::make('admin/login');
    }

    public function startseite() {
        if (Session::has('user')) {
            return Redirect::to('/uebersicht');
        }
        return View::make('admin/login');
    }

     public function uebersicht() {
        if (!Session::has('user')) {
            return Redirect::to('/login');
        }

        $mandanten = Mandant::all();

        return View::make('admin/uebersicht')->with('mandanten', $mandanten);
    }

	public function uebersicht2() {
        if (!Session::has('user')) {
            return Redirect::to('/login');
        }

        $mandanten = Mandant::all();

        return View::make('admin/uebersicht2')->with('mandanten', $mandanten);
    }
*/
/*
    public function drucken() {
        if (!Session::has('user')) {
            return Redirect::to('/login');
        }
        $mandant_id = Input::get('id');
        $mandant = Mandant::find($mandant_id);
        $orders = Order::where('mandant_id', '=', $mandant_id)->where('status_id', '=', 2)->get();
        return View::make('admin/drucken')->with('orders', $orders)->with('mandant', $mandant);
    }

	public function abrechnen() {
        if (!Session::has('user')) {
            return Redirect::to('/login');
        }
        $mandant_id = Input::get('id');
        $mandant = Mandant::find($mandant_id);
        $orders = Order::where('mandant_id', '=', $mandant_id)->get();
        return View::make('admin/abrechnen')->with('orders', $orders)->with('mandant', $mandant);
    }
*/
}
