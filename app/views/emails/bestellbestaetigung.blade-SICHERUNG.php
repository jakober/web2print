@extends('emails/emailmaster')

@section('content')
<h1>Vielen Dank für Ihre Bestellung.</h1>
<table>
    <tr>
        <td>Ihre Auftragsnummer:</td>
        <td class="number">{{$order->id}}</td>
    </tr>
    <tr>
        <td>Bestellmenge:</td>
        <td class="number">{{$order->menge}}</td>
    </tr>
</table>

<p>Sie können Ihren Bestellstatus unter <p><a href="{{$statusUrl}}" class="link">{{$statusUrl}}</a></p><p>einsehen.</p>
<?php
if (isset($infotext)) {
    echo $infotext;
}
?>
@endsection
