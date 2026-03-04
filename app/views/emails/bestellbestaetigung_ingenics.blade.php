@extends('emails/emailmaster')

@section('content')
<br><br><h1>Vielen Dank für Deine Bestellung.</h1>
<table>
    <tr>
        <td>Deine Auftragsnummer:</td>
        <td class="number">{{$order->id}}</td>
    </tr>
    <tr>
        <td>Bestellmenge:</td>
        <td class="number">{{$order->menge}}</td>
    </tr>
</table>

<p>Du kannst Deinen Bestellstatus unter <p><a href="{{$statusUrl}}" class="link">{{$statusUrl}}</a></p><p>einsehen.</p>
<?php
if (isset($infotext)) {
    echo $infotext;
}
?>
@endsection
