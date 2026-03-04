@extends('master')
@section('head')
{{HTML::script('/js/jquery-1.10.1.min.js')}}
{{HTML::style('/css/verwaltung.css')}}
@endsection

@section('content')
@if(isset($message))
<div class="message">{{{$message}}}</div>
@endif
<table class="list p">
    <tr class="custom-bg"><th>Anmeldung</th><th>Name / Bemerkung</th><th>Gruppe</th><th>Details</th></tr>
    @foreach($users as $i=>$o)
    <tr<?php echo $i & 1 ? ' class="odd"' : ''; ?>>
        <td class="left">{{{$o->username}}}</td>
        <td class="left">{{{$o->name}}}</td>
        <td class="left">{{{$o->gruppe()->first()->bezeichnung}}}</td>
        <td><form method="get" action="/verwaltung/benutzer/details"><input type="hidden" name="id" value="{{$o->id}}" /><input type="submit" class="button small" value="Details" /></form><form onsubmit="return confirm('Benutzer &quot;<?php echo htmlspecialchars($o->username); ?>&quot; löschen?')" method="post"><input type="hidden" name="id" value="{{$o->id}}" /><input type="hidden" name="action" value="delete" /><input type="submit" class="button small red" value="löschen" /></form></td>
    </tr>
    @endforeach
</table>
<p>
<form method="get" action="/verwaltung/neuer_benutzer">
<input type="submit" class="button" value="Neuen Benutzer anlegen" />
</form>
</p>
@endsection
