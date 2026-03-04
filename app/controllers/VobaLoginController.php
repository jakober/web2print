<?php

class VobaLoginController extends BaseController {

    public function login() {
        if ($this->mandant->id != 1 && $this->mandant->id != 6 && $this->mandant->id !=7) {
            App::abort(404);
        }

        $m = new MiscController();
        return $m->login();
    }

    public function autoLogin($key)
    {
        $key = urldecode($key);

        // User anhand des Keys + mandant_id suchen
        $user = User::where('mandant_id', '=', $this->mandant->id)
                    ->where('loginkey', '=', $key)
                    ->first();

        if ($user) {
            // User in Session speichern
            Session::put('user', $user);

            // Weiterleitung je nach Gruppe
            if ($user->gruppe_id == 1) {
                return Redirect::to('/auswahl');
            }

            // Standard-Weiterleitung
            return Redirect::to('/dashboard');
        }

        // Fallback wenn Key ungültig
        return Redirect::to('/')
            ->with('error', 'Autologin fehlgeschlagen.');
    }

}
