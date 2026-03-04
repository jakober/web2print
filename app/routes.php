<?php
/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

Route::get('fonts/convert', 'FontController@convert');
Route::get('order/{id}/pdf/{beschnitt?}', 'EditController@pdf');

/*Route::get('autologin/{token}', [
    'as'   => 'autologin',
    'uses' => 'VobaLoginController@autoLogin'
])->where('token', '.*');*/
Route::get('autologin/{key}', 'VobaLoginController@autoLogin')->where('key', '.*');


if (!preg_match('/^admin\..*/', getenv('HTTP_HOST'))) {
    Route::get('/', 'HomeController@startseite');


    Route::get('/auswahl', 'EditController@auswahl');
    Route::any('/gestaltung', 'EditController@edit');
    Route::get('/anschrift', 'EditController@anschrift');
    Route::get('/bestellung', 'EditController@bestellung');
    Route::post('/bestellung', 'EditController@bestellung_post');

    Route::get('/menge', 'EditController@menge');
    Route::post('/bestellabschluss', 'EditController@bestellabschluss');
    Route::get('/bestellstatus/{key}', 'MiscController@statusAnsicht');
    Route::post('/login', 'MiscController@login');
    Route::any('/logout', 'MiscController@logout');
    Route::any('/verwaltung/uebersicht', 'VerwaltungsController@uebersicht');
    Route::any('/verwaltung/uebersicht2', 'VerwaltungsController@uebersicht2');
    Route::any('/verwaltung/benutzer', 'VerwaltungsController@benutzer');
    Route::any('/verwaltung/benutzer/details', 'VerwaltungsController@benutzerDetails');
    Route::post('/verwaltung/freigabe', 'VerwaltungsController@freigabe');
    Route::post('/verwaltung/loeschen', 'VerwaltungsController@loeschen');
    Route::post('/verwaltung/saveImage', 'VerwaltungsController@saveImage');
    Route::any('/verwaltung/einstellungen', 'VerwaltungsController@einstellungen');
    Route::any('/pw', 'VerwaltungsController@pw');
    Route::any('/ajax/action/setBestellnummer', 'AjaxController@setBestellnummer');

    Route::any('/verwaltung/neuer_benutzer', 'VerwaltungsController@neuerBenutzer');

    Route::get('/verwaltung/details', 'VerwaltungsController@details');
    Route::post('/verwaltung/details', 'VerwaltungsController@details');

    Route::any('/verwaltung/bestellung_kopieren', 'VerwaltungsController@bestellung_kopieren');


    Route::any('/gestaltung/save', 'EditController@save');
    Route::post('/gestaltung/save', 'EditController@save');
    Route::get('/alter_browser', function() {
        return View::make('alter_browser');
    });
    Route::post('/verwaltung/updateorder', 'VerwaltungsController@updateOrder');
    Route::post('/verwaltung/updateorder2', 'VerwaltungsController@updateOrder2');
    Route::post('/verwaltung/template', 'VerwaltungsController@selectTemplate');
    Route::get('/verwaltung/historie', 'VerwaltungsController@historie');

    Route::get('login.html', 'VobaLoginController@login');

    Route::get('/flush', function() {
        Session::flush();
        return Redirect::to('/');
    });
} else {

    if (getenv('REMOTE_ADDR') == '217.7.235.96' || getenv('REMOTE_ADDR') == '80.147.216.14' || getenv('REMOTE_ADDR') == '62.152.164.11' || getenv('REMOTE_ADDR') == '116.202.44.81' || getenv('HTTP_HOST') == 'admin.web2print.bairle.local') {
//Route::get('/', 'AdminController@startseite');
//Route::get('/login', 'AdminController@login');
//Route::post('/login', 'AdminController@login_post');
//Route::get('/uebersicht', 'AdminController@uebersicht');
//Route::get('/uebersicht2', 'AdminController@uebersicht2');
//Route::get('/drucken', 'AdminController@drucken');
//Route::get('/abrechnen', 'AdminController@abrechnen');

        Route::any('/ajax/uebersicht', "AjaxController@uebersicht");
        Route::any('/ajax/uebersicht/{id}', "AjaxController@uebersichtDetails");

        Route::any('/ajax/action/setGedruckt', 'AjaxController@setStatusGedruckt');
        Route::any('/ajax/action/setBerechnet', 'AjaxController@setStatusBerechnet');
        Route::get('/ajax/lieferscheine/uebersicht', 'AjaxController@lieferscheineUebersicht');
        Route::get('/ajax/lieferscheine/uebersicht/{m}', 'AjaxController@lieferscheineMandant');

        Route::any('/ajax/action/lieferscheineDrucken', 'AjaxController@lieferscheineDrucken');

//Route::any('/ajax/rg-uebersicht', 'AjaxController@rgUebersicht');

        Route::get('/print/{id}', function($id) {
            $order = Order::where('id', '=', $id)->first();
            $pdf = $order->createPDF(true, 0);
            $pdf->output();
        });
        Route::get('/pdf/{id}', function($id) {
            $order = Order::where('id', '=', $id)->first();
            $pdf = $order->createPDF(false, 0);
            $pdf->output();
        });

        Route::any('/ajax/r/uebersicht', "AbrechnungsController@uebersicht");
        Route::any('/ajax/r/bestellungen/{mandant}', "AbrechnungsController@bestellungenMandant");

        Route::get('test/{id}', function($id) {
            $o = Order::find($id);
            return $o->getPreis();
        });
        Route::get('update', function() {
            $orders = Order::all();

            foreach ($orders as $o) {
                $o->updateFreitext1();
                $o->preis = $o->getPreis();
                $o->save();
            }
        });
    }
}
//Route::get('/backend/pdf', 'ExportController@pdfDownload');
