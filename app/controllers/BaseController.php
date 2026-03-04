<?php

class BaseController extends Controller {

    public function __construct() {
        $host = getenv('HTTP_HOST');

        $this->mandant = Mandant::where('hostname', '=', $host)->first();

        /*
        if (Session::has('user_id')) {
            $this->user = User::find(Session::get('user_id'));
            if ($this->user == null) {
                Session::forget('user_id');
                Return Redirect::to('/');
            }
            $ausgabe = $this->mandant->ausgaben()->next(1)->first();

            View::share('naechsteAusgabe', $ausgabe);
        } else {
            $this->user = null;
        }
        View::share('user', $this->user);
         *
         */
        View::share('mandant', $this->mandant);

        if(Session::has('user')) {
            View::share('registered_user',$this->registered_user=Session::get('user'));
        }
        View::share('__rev',Config::get('app.revision'));
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout() {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

}
