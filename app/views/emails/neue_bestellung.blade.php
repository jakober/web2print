@extends('emails/emailmaster')

@section('content')
    <br><br><h1>Eine neue Bestellung wurde abgesendet:</h1>
    <p>Auftragsnummer: {{$order->id}}<br />
    Name: {{$order->name}}<br />
    Menge: {{$order->menge}}</p>
    <p>Details:<a href="{{{$detailsUrl}}}">{{{$detailsUrl}}}</a>
@endsection
