<?php

class ExportController extends BaseController {
    public function pdfDownload() {
        $margin = Input::has('margin') ? Input::get('margin') : 0;
        $print = Input::has('print') ? (Input::get('print')?true:false) : false;
        $id = Input::get('id');
        $order = Order::find($id);
    }
}