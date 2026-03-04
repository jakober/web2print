@extends('master')
@section('head')
<style type="text/css">
#main { border:solid 10px red }
div { box-shadow: none !important; }
</style>
@endsection

@section('content')
<h1>Sie verwenden einen veralteten Browser</h1>
<img src="/img/sadface.jpg" width="385" height="321" />
<p>Der Browser, den Sie verwenden, unterstützt keine Vektorgrafiken. Das Bearbeiten der Visitenkarten mit Live-Vorschau ist damit nicht möglich.</p>
<p>Wir empfehlen Internet Explorer 9, noch besser 10, oder einen aktuellen Firefox, Google Chrome, Safari oder Opera</p>
@endsection